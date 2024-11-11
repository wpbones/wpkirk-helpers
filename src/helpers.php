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
   * @param string|callable $strFunc The content for the section header.
   * @return void
   */
  function wpkirk_section($strFunc)
  {
    if (is_string($strFunc) && !empty($strFunc)) {
      $title = $strFunc;
    }
    if (is_callable($strFunc)) {
      $title = $strFunc();
    }

    // transform the title is snake case
    $id = strtolower(str_replace(' ', '-', $title));

    echo "<hr/><h1 class=\"wpkirk-section\" id=\"$id\">";
    echo $title;
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
   * @param array $options An array of options for the code block.
   *  The following options are supported:
   *  'eval' => bool - If true, the code will be evaluated.
   *  'language' => string - The language of the code block.
   *  'details' => bool - If true, the code block will be wrapped in a details element.
   *  'line-numbers' => bool - If true, line numbers will be displayed.
   * @return void
   */
  function wpkirk_code($func = '', $options = [])
  {

    $defaults = [
      'evail' => false,
      'language' => 'php',
      'details' => true,
      'line-numbers' => false,
    ];

    $options = array_merge($defaults, $options);

    $eval = $options['eval'];
    $language = $options['language'];
    $openDetails = $options['details'];
    $lineNumbers = $options['line-numbers'];

    // if $func string starts with "@" we will load a file
    if (substr($func, 0, 1) === '@') {
      $basePath = WPKirk()->basePath;
      $filename = ltrim($func, '@');
      $file = file_get_contents($basePath . $filename);
      $file = trim(rtrim($file, PHP_EOL));

      // get the extension of the file
      $language = pathinfo($basePath . $filename, PATHINFO_EXTENSION);

      // work around for jsx and tsx as they seem to be not supported by prismjs
      if (in_array($language, ['jsx', 'tsx'])) {
        $language = 'ts';
      }

      $func = htmlspecialchars($file);
    }

    $replaceBackticksWithCode = function (string $text, string $class = 'inline'): string {
      $pattern = '/`(.*?)`/';
      $replacement = '<code class="' . htmlspecialchars($class) . '">$1</code>';
      return preg_replace($pattern, $replacement, $text);
    };

    $result = $replaceBackticksWithCode($func);

    if (!empty($filename)) {
      echo '<div class="wpkirk-filename">' . ltrim($filename, '/') . '</div>' . PHP_EOL;
    }

    echo '<pre' . ($lineNumbers ? ' class="line-numbers"' : '') . '><code class="language-' . $language . '">';
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
