<?php

if (!function_exists('wpkirk_toc')) {

  /**
   * Table of Contents
   */
  function wpkirk_toc()
  {
    $buffer = ob_get_contents();

    $pattern = '/<h2 class="wpkirk-section" id="([^"]+)">([^<]+)<\/h2>/i';
    $matches = [];

    preg_match_all($pattern, $buffer, $matches, PREG_SET_ORDER);

    error_log(print_r($matches, true));

    $toc = '<div class="wp-kirk-toc clearfix"><ul>';

    foreach ($matches as $match) {
      $id = $match[1];
      $subtitle = $match[2];
      $toc .= '<li><a href="#' . htmlspecialchars($id) . '">' . htmlspecialchars($subtitle) . '</a></li>';
    }

    $toc .= '</ul></div>';

    echo $toc;

    ob_end_flush();
  }
}

if (!function_exists('wpkirk_output')) {

  /**
   * Output
   */
  function wpkirk_output($func, $language = 'json')
  {
    echo '<details>';
    echo '<summary>' . __('Output', 'wp-kirk') . '</summary>';
    echo wpkirk_execute_code($language, $func);
    echo '</details>';
  }
}


if (!function_exists('wpkirk_section')) {

  /**
   * Section
   */
  function wpkirk_section($id, $strFunc)
  {
    echo "<h2 class=\"wpkirk-section\" id=\"$id\">";
    if (is_string($strFunc) && !empty($strFunc)) {
      echo $strFunc;
    }
    if (is_callable($strFunc)) {
      echo $strFunc();
    }
    echo '</h2>';
  }
}

if (!function_exists('wpkirk_code')) {

  /**
   * Code
   */
  function wpkirk_code($language = 'php', $func = '')
  {
    echo '<pre><code class="language-' . $language . '">';
    echo $func;
    echo '</code></pre>';
  }
}

if (!function_exists('wpkirk_execute_code')) {

  /**
   * Execute Code
   */
  function wpkirk_execute_code($language = 'php', $func = '')
  {
    echo '<pre><code class="language-' . $language . '">';
    if (is_callable($func)) {
      echo $func();
    }

    if (is_string($func)) {
      echo eval($func);
    }

    echo '</code></pre>';
  }
}
