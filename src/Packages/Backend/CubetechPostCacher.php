<?php

namespace Cubetech\Packages\Backend;

use \Cubetech\Packages\IPackage;
use \Cubetech\Base\CubetechPost;
use \Cubetech\PageBuilder\PageBuilder;

/**
 * Package to cache the PageBuilder content of any Post into its
 * post_content, to enable searching of the same content
 *
 * @author Alex Scherer <alex.scherer@cubetech.ch>
 * @since 1.0.0
 * @version 1.0.1
 */
class CubetechPostCacher implements IPackage
{

    /**
     * This switch is set to true before a post is saved by this package
     * to avoid an endless save-loop
     *
     * @var bool
     *
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    private static $inProgress = false;

    /**
     * Entrypoint of this Package
     * Hooks on the save_post action to catch the post save event
     *
     * @return void
     *
     * @static
     * 
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    public static function run()
    {
        add_action('save_post', __CLASS__ . '::compilePagebuilderToContent', 10, 3);
    }

    /**
     * Compiles the content of the PageBuilder of a saved post into
     * the post_content to enable search through the PageBuilder content
     * This method is called each time any post is saved.
     *
     * @param int $postId
     * @param WP_Post $post
     * @param bool $update
     * @return void
     * 
     * @static
     *
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function compilePagebuilderToContent($postId, $post, $update)
    {

        if (self::$inProgress) {
            return;
        }

        if ($post->post_type !== 'page' && $post->post_type !== 'post') {
            return;
        }

        if ($post->post_type === 'page') {
            $templateSlug = get_page_template_slug($postId);
            if ($templateSlug !== 'template-pagebuilder.php') {
                return;
            }
        }

        self::$inProgress = true;

        $pagebuilder = new PageBuilder($postId);
        if (!$pagebuilder->hasComponents()) {
            self::processWordCount($postId, $post->post_content);
            self::$inProgress = false;
            return;
        }
        $content = $pagebuilder->renderToString();

        $postArray = [
            'ID'           => $postId,
            'post_content' => strip_tags(wp_slash($content)),
        ];

        wp_update_post($postArray);

        self::processWordCount($postId, $content);

        self::$inProgress = false;
    }

    /**
     * Processes the contentString, counts the words and saves them
     * to the post meta
     *
     * @param string $contentString
     * @return void
     *
     * @static
     * 
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    protected static function processWordCount($postID, $contentString)
    {
        $wordCount = self::getWordCount($contentString);
        $readingTime = self::calculateReadingTime($wordCount);
        self::saveReadingTime($postID, $readingTime);
    }

    /**
     * Removes newlines and splits strings into words
     *
     * @return int count
     *
     * @static
     * 
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    protected static function getWordCount($contentString)
    {
        $contentString = strip_tags(str_replace([
            "\n",
            "\r",
        ], '', $contentString));
        return count(explode(' ', $contentString));
    }

    /**
     * Returns minutes rounded
     * If the reading time is less than 1, return 1
     *
     * @param int $wordCount
     * @return int minutes
     *
     * @static
     * 
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    protected static function calculateReadingTime($wordCount)
    {
        $time = $wordCount / 180;
        if ($time < 1) {
            return 1;
        } else {
            $time = explode('.', $time);
            if (is_array($time) && count($time) === 2) {
                $seconds = $time[1] * 0.6;
                if ($seconds < 30) {
                    return $time[0];
                } else {
                    return $time[0] + 1;
                }
            } else {
                return $time[0];
            }
        }
    }

    /**
     * Saves the readingtime to the postmeta
     *
     * @param int $postID
     * @param int $readingTime
     * @return void
     *
     * @static
     * 
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    protected static function saveReadingTime($postID, $readingTime)
    {
        if (!get_post_meta($postID, 'ct_reading_time')) {
            add_post_meta($postID, 'ct_reading_time', $readingTime);
        } else {
            update_post_meta($postID, 'ct_reading_time', $readingTime);
        }
        self::checkReadingTimeMeta($postID);
    }

    /**
     * Somehow people can save to same meta twice or more
     * this function prevents the frontent from getting multiple values
     * and tries to stringify an array
     *
     * @return void
     *
     * @static
     * 
     * @author Alex Scherer <alex.scherer@cubetech.ch>
     * @since 1.0.0
     */
    protected static function checkReadingTimeMeta($postID)
    {
        $readingMeta = get_post_meta($postID, 'ct_reading_time');
        if (count($readingMeta) > 1) {
            delete_post_meta($postID, 'ct_reading_time');
            add_post_meta($postID, 'ct_reading_time', $readingMeta[count($readingMeta) - 1]);
        }
    }
}
