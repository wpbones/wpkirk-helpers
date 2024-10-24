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
   * Outputs the result of a function within a details HTML element.
   *
   * This function executes the provided function and displays its output
   * within a details HTML element. The output is formatted based on the specified language.
   *
   * @param callable|string $func The function to execute or the code to evaluate.
   * @param string $language The language for syntax highlighting (default: 'json').
   * @return void
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
   * Outputs code within a preformatted code block.
   *
   * This function displays the provided code within a preformatted code block
   * with syntax highlighting based on the specified language.
   *
   * @param string $language The language for syntax highlighting (default: 'php').
   * @param string $func The code to display.
   * @return void
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
   * Executes the provided code and outputs the result within a preformatted code block.
   *
   * This function executes the provided code (either a callable function or a string)
   * and displays the result within a preformatted code block with syntax highlighting
   * based on the specified language.
   *
   * @param string $language The language for syntax highlighting (default: 'php').
   * @param callable|string $func The function to execute or the code to evaluate.
   * @return void
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
