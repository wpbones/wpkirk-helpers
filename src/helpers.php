<?php

if (!function_exists('wpkirk_toc')) {

  /**
   * Generates a Table of Contents (TOC) based on the sections in the buffer.
   *
   * This function scans the output buffer for section headers and generates a TOC.
   * The TOC is then displayed at the beginning of the content.
   *
   * @return void
   */
  function wpkirk_toc($title = "")
  {
    $buffer = ob_get_contents();

    $pattern = '/<h1 class="wpkirk-section" id="([^"]+)">([^<]+)<\/h1>/i';
    $matches = [];

    preg_match_all($pattern, $buffer, $matches, PREG_SET_ORDER);

    error_log(print_r($matches, true));

    $toc = '<div class="wp-kirk-toc clearfix">';
    $toc .= empty($title) ? '' : '<h2>' . $title . '</h2>';
    $toc .= '<ul>';

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

if (!function_exists('wpkirk_section')) {

  /**
   * Outputs a section header with the specified ID and content.
   *
   * This function generates an H2 header with the specified ID and content.
   * The content can be a string or a callable function.
   *
   * @param string $id The ID for the section header.
   * @param string|callable $strFunc The content for the section header.
   * @return void
   */
  function wpkirk_section($id, $strFunc)
  {
    echo "<hr/><h1 class=\"wpkirk-section\" id=\"$id\">";
    if (is_string($strFunc) && !empty($strFunc)) {
      echo $strFunc;
    }
    if (is_callable($strFunc)) {
      echo $strFunc();
    }
    echo '</h1><hr/>';
  }
}

if (!function_exists('wpkirk_code')) {

  /**
   * Outputs code within a preformatted code block.
   *
   * This function displays the provided code within a preformatted code block
   * with syntax highlighting based on the specified language.
   *
   * @param string $func The code to display. If the string starts with "@", the function will load a file.
   * @param bool $eval Whether to evaluate the code (default: false).
   * @param string $language The language for syntax highlighting (default: 'php').
   * @return void
   */
  function wpkirk_code($func = '', $eval = false, $language = 'php', $openDetails = true)
  {
    // if $func string starts with "@" we will load a file
    if (substr($func, 0, 1) === '@') {
      $basePath = WPKirk()->basePath;
      $filename = ltrim($func, '@');
      $file = file_get_contents($basePath . $filename);

      $func = htmlspecialchars($file);
    }

    $replaceBackticksWithCode = function (string $text, string $class = 'inline'): string {
      $pattern = '/`(.*?)`/';
      $replacement = '<code class="' . htmlspecialchars($class) . '">$1</code>';
      return preg_replace($pattern, $replacement, $text);
    };

    $result = $replaceBackticksWithCode($func);

    echo '<pre><code class="language-' . $language . '">';
    echo $result;
    echo '</code></pre>';

    if ($eval) {
      echo '<details ' . ($openDetails ? 'open' : '') . '>';
      echo '<summary>' . __('Output', 'wp-kirk') . '</summary>';
      echo '<pre><code class="language-txt">';
      echo eval($func);
      echo '</code></pre>';
      echo '</details>';
    }
  }
}

if (!function_exists('wpkirk_md')) {
  /**
   * Outputs Markdown text with inline code formatting.
   *
   * This function displays the provided Markdown text with inline code formatting.
   * The code is wrapped in a <code> element with the specified language class.
   *
   * @param string $text The Markdown text to display.
   * @param string $language The language for syntax highlighting (default: '').
   * @return void
   */
  function wpkirk_md(string $text, string $language = '')
  {
    $pattern = '/`(.*?)`/';
    $replacement = '<code class="language-' . $language . ' inline">$1</code>';
    echo preg_replace($pattern, $replacement, $text);
  }
}
