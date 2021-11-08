<?php

namespace TypiCMS\Modules\Forum\Helpers;

class ForumHelper
{
    /**
     * Convert any string to a color code.
     *
     * @param string $string
     *
     * @return string
     */
    public static function stringToColorCode($string)
    {
        $code = dechex(crc32($string));

        return mb_substr($code, 0, 6);
    }

    /**
     * User link.
     *
     * @param mixed $user
     *
     * @return string
     */
    public static function userLink($user)
    {
        $url = config('typicms.forum.user.relative_url_to_profile', '');

        if ('' === $url) {
            return '#_';
        }

        return static::replaceUrlParameter($url, $user);
    }

    /**
     * Replace url parameter.
     *
     * @param string $url
     * @param mixed  $source
     *
     * @return string
     */
    private static function replaceUrlParameter($url, $source)
    {
        $parameter = static::urlParameter($url);

        return str_replace('{'.$parameter.'}', $source[$parameter], $url);
    }

    /**
     * Url parameter.
     *
     * @param string $url
     *
     * @return string
     */
    private static function urlParameter($url)
    {
        $start = mb_strpos($url, '{') + 1;

        $length = mb_strpos($url, '}') - $start;

        return mb_substr($url, $start, $length);
    }

    /**
     * This function will demote H1 to H2, H2 to H3, H4 to H5, etc.
     * this will help with SEO so there are not multiple H1 tags
     * on the same page.
     *
     * @param string $html
     *
     * @return string
     */
    public static function demoteHtmlHeaderTags($html)
    {
        $originalHeaderTags = [];
        $demotedHeaderTags = [];

        foreach (range(100, 1) as $index) {
            $originalHeaderTags[] = '<h'.$index.'>';

            $originalHeaderTags[] = '</h'.$index.'>';

            $demotedHeaderTags[] = '<h'.($index + 1).'>';

            $demotedHeaderTags[] = '</h'.($index + 1).'>';
        }

        return str_ireplace($originalHeaderTags, $demotedHeaderTags, $html);
    }
}
