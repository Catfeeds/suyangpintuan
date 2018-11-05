<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


if (!function_exists('build_page_url')) {
    function build_page_url($page, $page_param = '', $params = '')
    {
        if (!$page_param) {
            $page_param = 'page';
        }
        if (!$params) {
            $params = $_GET;
        }
        $params[$page_param] = $page;
        $query = http_build_query($params);
        $base_url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $base_url . '?' . $query;
    }
}


/**
 * Smarty {pager} function plugin
 * params: current_page, links, separator, link_format, empty_format, current_format
 *
 * Type:     function<br>
 * Name:     pager<br>
 * Purpose:  output a pager <br>
 * @author liuw
 * @param array
 *
 */
function smarty_function_pager($params = array())
{

    if (isset($params['params']) && $_params = $params['params']) {
        $params = $_params;
    }

    $paginator = isset($params['paginator']) ? $params['paginator'] : null;

    if ($paginator instanceof util_Paginator) {
        $page = $paginator->getPage();
        $limit = $paginator->getLimit();
        $count = $paginator->getCount();
        $width = 5;

        if (!$page) {
            $page = 1;
        }

        if (!$limit || !$count) {
            return '';
        }

        $total_page = ceil($count / $limit);

        $has_previous = $page > 1 ? true : false;
        $has_next = $page >= $total_page ? false : true;

        $window_start = $page - $width;
        $window_end = $page + $width;

        if ($window_start < 1) {
            $window_end = $window_end - $window_start;
            $window_start = 1;
        }
        if ($window_end > $total_page) {
            $window_start = $window_start - ($window_end - $total_page);
            $window_end = $total_page;
        }
        if ($window_start < 1) {
            $window_start = 1;
        }
        if ($window_end > $total_page) {
            $window_end = $total_page;
        }

        if($total_page == 1)return '';

        $output = '<div class="pagination"><ul>';
        if ($has_previous) {
            $output .= '<li><a href="' . build_page_url($page - 1) . '">&laquo;</a></li>';
        }
        if ($window_start > 1) {
            $output .= '<li><a href="' . build_page_url(1) . '">1</a></li>';
            $output .= '<li class="disabled"><a href="javascript:">...</a></li>';
        }
        for ($i = $window_start; $i <= $window_end; $i++) {
            if ($i == $page) {
                $output .= '<li class="active"><a href="' . build_page_url($i) . '">' . $i . '</a></li>';
            } else {
                $output .= '<li><a href="' . build_page_url($i) . '">' . $i . '</a></li>';
            }
        }
        if ($window_end < $total_page) {
            $output .= '<li class="disabled"><a href="javascript:">...</a></li>';
            $output .= '<li><a href="' . build_page_url($total_page) . '">' . $total_page . '</a></li>';
        }
        if ($has_next) {
            $output .= '<li><a href="' . build_page_url($page + 1) . '">&raquo;</a></li>';
        }

        $output .= '</ul></div>';
        return $output;
    }

    $link_format = $params['link_format'] ? $params['link_format'] : "<a href='#URL#'>#PAGE#</a>";
    $empty_format = $params['empty_format'] ? $params['empty_format'] : "#PAGE#";
    $current_format = $params['current_format'] ? $params['current_format'] : "<strong>#PAGE#</strong>";
    $current_page = $params['current_page'];
    $total_page = isset($params['total_page']) ? $params['total_page'] : 0;
    $links = $params['links'];
    $link_width = 9;
    if (!$links) {
        if (!$total_page) {
            return '';
        }
        $width_count = floor($link_width / 2);
        $start_page = min($current_page - $width_count, $total_page - $link_width + 1);
        if ($start_page < (1 + $width_count)) {
            $start_page = 1;
        }

        $end_page = max($start_page + $link_width - 1, $current_page + $width_count);
        if ($end_page > ($total_page - $width_count)) {
            $end_page = $total_page;
        }

        $link_array = range($start_page, $end_page);
        $before_array = array();
        if ($start_page >= (1 + $width_count)) {
            $before_array = array(1, 2);
            if ($start_page > ($width_count)) {
                $before_array[] = '...';
            }
        }

        $after_array = array();
        if ($end_page <= ($total_page - $width_count)) {
            $after_array = array($total_page - 1, $total_page);
            if ($end_page < ($total_page - $width_count + 1)) {
                array_unshift($after_array, '...');
            }
        }
        $link_array = array_merge($before_array, $link_array, $after_array);
        foreach ($link_array as $idx => $page) {
            if (is_numeric($page)) {
                $links[$page] = $page; //str_replace('#PAGE#', $page, $link_format);
            } else {
                $links['space_' . $idx] = $idx;
            }
        }
    }
    $link_array = array();
    foreach ($links as $key => $url) {
        if (is_numeric($key)) {
            if ($key == $current_page) {
                $format = $current_format;
            } else {
                $format = $link_format;
            }
            $link = str_replace('#PAGE#', $key, str_replace('#URL#', $url, $format));
        } else {
            $link = str_replace('#PAGE#', $url, $empty_format);
        }
        $link_array[] = $link;
    }
    if (count($link_array) < 2) {
        return '';
    }
    return implode($params['separator'] ? $params['separator'] : '', $link_array);
}

/* vim: set expandtab: */
?>
