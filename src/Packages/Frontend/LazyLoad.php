<?php

namespace Cubetech\Packages\Frontend;

use \Cubetech\Packages\IPackage;

/**
 * Package class providing a unified logic for LazyLoading post
 * cards via an admin_ajax endpoint
 *
 * @author Alex Scherer <alex.scherer@cubetech.ch>
 * @since 1.0.0
 */
class LazyLoad implements IPackage
{
    /**
     * Namespace for cards as string to instantiate cards for the given posttype
     */
    const CARD_NAMESPACE = '\\Cubetech\\Cards\\';

    /**
     * Holds a collection of errorCodes together with their http
     * status codes and messages
     *
     * @var array
     */
    protected static $errorCodes;

    /**
     * Boolean Flag signifying whether there are more pages to
     * load using the last used settings
     *
     * @var bool
     */
    protected static $hasMorePages;

    /**
     * Hooking point for this package; Initializes the error codes
     * and registers the admin_ajax endpoint
     *
     * @return void
     *
     * @static
     * 
     * @author Alex Scherer <alex.scherer@cuebtech.ch>
     * @since 1.0.0
     */
    public static function run()
    {
        self::initializeErrorCodes();
        add_action('wp_ajax_ct_lazyload', __CLASS__. '::handleLazyLoadCall');
        add_action('wp_ajax_nopriv_ct_lazyload', __CLASS__.'::handleLazyLoadCall');
    }

    /**
     * Initializes the array of error codes for later use
     *
     * @return void
     *
     * @static
     * 
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    protected static function initializeErrorCodes()
    {
        if (empty(self::$errorCodes)) {
            self::$errorCodes = [];
        }

        self::$errorCodes[0] = [
            'status'  => 400,
            'message' => _x('Parameter post_type must be set.', 'LazyLoad Package strings', 'wptheme-basetheme')
        ];
        self::$errorCodes[1] = [
            'status'  => 404,
            'message' => _x('Posttype "%s" not found', 'LazyLoad Package strings', 'wptheme-basetheme')
        ];
        self::$errorCodes[2] = [
            'status'  => 400,
            'message' => _x('Parameter page must be greater than zero', 'LazyLoad Package strings', 'wptheme-basetheme')
        ];
        self::$errorCodes[3] = [
            'status'  => 400,
            'message' => _x('Parameter posts_per_page must be greater than zero', 'LazyLoad Package strings', 'wptheme-basetheme')
        ];
        self::$errorCodes[4] = [
            'status'  => 400,
            'message' => _x('Parameter filter must be an array', 'LazyLoad Package strings', 'wptheme-basetheme')
        ];
        self::$errorCodes[5] = [
            'status'  => 404,
            'message' => _x('No cards found', 'LazyLoad Package strings', 'wptheme-basetheme')
        ];
    }

    /**
     * Sends a specific error message, looking it up using the
     * given $code
     *
     * @param int $code
     * @param mixed ...$replacements
     * @return void
     *
     * @static
     * 
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    protected static function sendError($code, ...$replacements) {
        if (empty(self::$errorCodes[$code])) {
            http_response_code(500);
            exit;
        }
        $httpCode = self::$errorCodes[$code]['status'];
        $message = '';
        if (empty($replacements)) {
            $message = self::$errorCodes[$code]['message'];
        } else {
            $message = sprintf(self::$errorCodes[$code]['message'], $replacements);
        }
        self::sendResponseCode($httpCode, $code, $message);
        echo $message . ' (' . $code . ')';
        exit;
    }

    /**
     * Sends a customized HTTP response code and message
     *
     * @param int $httpCode
     * @param int $appCode
     * @param string $message
     * @return void
     *
     * @static
     * 
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    protected static function sendResponseCode($httpCode, $appCode, $message) {
        $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
        header($protocol . ' ' . $httpCode . ' ' . $message . ' (' . $appCode . ')');
        $GLOBALS['http_response_code'] = $httpCode;
    }

    /**
     * AJAX request handler for the ct_lazyload action
     *
     * @return void
     *
     * @static
     * 
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    public static function handleLazyLoadCall()
    {
        $params = [];
        if (!empty($_POST['post_type'])) {
            $params['post_type'] = sanitize_text_field($_POST['post_type']);
            $postTypeObject = get_post_type_object($params['post_type']);
            if (empty($postTypeObject)) {
                self::sendError(1,$params['post_type']);
            }
        } else {
            self::sendError(0);
        }

        if (!empty($_POST['page'])) {
            $params['page'] = (int)$_POST['page'];
            if ($params['page'] < 1) {
                self::sendError(2);
            }
        }

        if (!empty($_POST['posts_per_page'])) {
            $params['posts_per_page'] = (int)$_POST['posts_per_page'];
            if ($params['posts_per_page'] < 1) {
                self::sendError(3);
            }
        }

        if (!empty($_POST['wrapper_class'])) {
            $params['wrapper_class'] = $_POST['wrapper_class'];
        }

        if (!empty($_POST['filter'])) {
            if (is_array($_POST['filter'])) {
                $params['filter'] = $_POST['filter'];
            } else {
                self::sendError(4);
            }
        }

        if (!empty($_POST['card'])) {
            $params['card'] = $_POST['card'];
        }

        if (!empty($_POST['ancestor'])) {
            if (is_numeric($_POST['ancestor'])) {
                $params['ancestor'] = (int)$_POST['ancestor'];
            } elseif ($_POST['ancestor'] === 'none') {
                $params['ancestor'] = false;
            }
        }

        if (!empty($_POST['children'])) {
            if ($_POST['children'] === 'true') {
                $params['children'] = true;
            } else {
                $params['children'] = false;
            }
        }

        $cards = self::fetchCards($params);
        self::sendHtmlResponse($cards);
    }

    protected static function getPosts($params)
    {
        $posts = [];
        if (isset($params['ancestor']) && isset($params['children'])) {
            $posts = self::getHierarchicalPosts($params);
            if (count($posts) > $params['posts_per_page']) {
                $posts = array_slice($posts, ($params['page']-1)*$params['posts_per_page']);
            }
        } else {
            $posts = self::getStandardPosts($params);
        }
        return $posts;
    }

    protected static function getStandardPosts($params)
    {
        $queryParams = [
            'post_status'     => 'publish',
            'post_type'       => $params['post_type'],
            'posts_per_page'  => $params['posts_per_page'],
            'paged'           => $params['page'],
        ];
        if (!empty($params['filter'])) {
            $queryParams['tax_query'] = [];
            if (count($params['filter']) > 1) {
                $queryParams['tax_query']['relation'] = 'AND';
            }
            foreach ($params['filter'] as $filter) {
                if (!empty($filter['name']) && !empty($filter['value'])) {
                    $terms = null;
                    if (strpos($filter['value'], ',') === false) {
                        $terms = $filter['value'];
                    } else {
                        $terms = explode(',', $filter['value']);
                    }
                    $queryParams['tax_query'][] = [
                        'taxonomy'  => $filter['name'],
                        'field'     => 'term_id',
                        'terms'     => $terms
                    ];
                }
            }
        }

        $postQuery = new \WP_Query($queryParams);
        if ($postQuery->max_num_pages > $params['page']) {
            self::$hasMorePages = true;
        } else {
            self::$hasMorePages = false;
        }
        return $postQuery->posts;
    }

    protected static function getHierarchicalPosts($params)
    {
        $posts = [];
        $queryParams = [
            'post_status'  => 'publish',
            'post_type'    => $params['post_type'],
            'post_parent'  => $params['ancestor'],
            'numberposts'  => -1,
        ];

        $potentialPosts = get_posts($queryParams);

        foreach ($potentialPosts as $key => $potentialPost) {
            $subQueryParams = $queryParams;
            $subQueryParams['post_parent'] = $potentialPost->ID;
            $subPosts = get_posts($subQueryParams);

            if (empty($subPosts)) {
                $posts[] = $potentialPost;
                if (self::haveEnoughPosts($params, $posts)) {
                    return $posts;
                }
            } else {
                if ($params['children'] === true) {
                    $posts[] = $potentialPost;
                    if (self::haveEnoughPosts($params, $posts)) {
                        return $posts;
                    }
                } else {
                    foreach ($subPosts as $subPost) {
                        if (self::haveEnoughPosts($params, $posts)) {
                            return $posts;
                        } else {
                            $posts[] = $subPost;
                        }
                    }
                }
            }
        }
        return $posts;
    }

    protected static function haveEnoughPosts($params, $posts)
    {
        if (count($posts) === $params['posts_per_page'] * $params['page']) {
            self::$hasMorePages = true;
            return true;
        }
        return false;
    }

    /**
     * Fetches post cards for the given parameters
     *
     * @param array $params
     * @return array
     *
     * @static
     * 
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    protected static function fetchCards($params)
    {
        $cards = [];

        $posts = self::getPosts($params);

        if (!empty($posts)) {
            foreach ($posts as $post) {
                if (empty($params['card'])) {
                    $card = self::tryGetCardByPosttype($params['post_type'], $post);
                } else {
                    $card = self::tryGetCardByCardName($params['card'], $post);
                }
                $card->wrapperClass = $params['wrapper_class'] ?? "";
                if (!empty($card)) {
                    $cards[] = $card;
                }
            }
        }
        return $cards;
    }

    protected static function tryGetCardByCardName($cardName, $post)
    {
        $fullCardName = self::CARD_NAMESPACE . $cardName;
        if (class_exists($fullCardName)) {
            return new $fullCardName($post->ID);
        } else {
            return false;
        }
    }

    /**
     * Tries to get a card for the post / post_type combination
     *
     * @param string $postType
     * @param WP_Post $post
     * @return BaseCard
     *
     * @static
     * 
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    protected static function tryGetCardByPosttype($postType, $post)
    {
        if ($postType === 'post') {
            $cardName = self::CARD_NAMESPACE . 'NewsCard';
        } elseif ($postType === 'churchservice' || $postType === 'ideas') {
            $cardName = self::CARD_NAMESPACE . 'MaterialrentCard';
        } else {
            $cardName = self::CARD_NAMESPACE . ucwords($postType) . 'Card';
            if (!class_exists($cardName)) {
                $cardName = self::CARD_NAMESPACE . 'StandardCard';
            }
        }
        return new $cardName($post->ID);
    }

    /**
     * Sends a HTML Response using the given $cards array
     *
     * @param array $cards
     * @return void
     *
     * @static
     * 
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    protected static function sendHtmlResponse($cards)
    {
        if (empty($cards)) {
            echo 0;
            exit;
        }
        foreach ($cards as $card) {
            $card->render();
        }
        if (self::$hasMorePages) {
            echo 1;
        } else {
            echo 0;
        }
        exit;
    }
}