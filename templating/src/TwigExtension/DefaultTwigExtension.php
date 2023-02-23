<?php

namespace Drupal\templating\TwigExtension;

/**
 * Class DefaultTwigExtension.
 */
class DefaultTwigExtension extends \Twig_Extension
{

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getNodeVisitors()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('spacer_top', ['Drupal\templating\TwigExtension\DefaultTwigExtension', 'spacer_top_twig']),
            new \Twig_SimpleFunction('spacer_bottom', ['Drupal\templating\TwigExtension\DefaultTwigExtension', 'spacer_bottom_twig']),

            new \Twig_SimpleFunction('file_exists', ['Drupal\templating\TwigExtension\DefaultTwigExtension', 'file_exists_twig']),

            new \Twig_SimpleFunction('template', ['Drupal\templating\TwigExtension\DefaultTwigExtension', 'template_twig']),
            new \Twig_SimpleFunction('render_node_inline_template', ['Drupal\templating\TwigExtension\DefaultTwigExtension', 'render_node_inline_template_twig']),
            new \Twig_SimpleFunction('render_template_node', ['Drupal\templating\TwigExtension\DefaultTwigExtension', 'render_template_node_twig']),
            new \Twig_SimpleFunction('render_template_block', ['Drupal\templating\TwigExtension\DefaultTwigExtension', 'render_template_block_twig']),

            new \Twig_SimpleFunction('render_inline_template', ['Drupal\templating\TwigExtension\DefaultTwigExtension', 'render_inline_template_twig']),
            new \Twig_SimpleFunction('DRUPAL_ROOT', ['Drupal\templating\TwigExtension\DefaultTwigExtension', 'DRUPAL_ROOT_TWIG']),
            new \Twig_SimpleFunction('path_templating', ['Drupal\templating\TwigExtension\DefaultTwigExtension', 'path_templating']),

        ];
    }
    public static function render_template_block_twig($content)
    {   $entity = false ;
        $is_edit_layout_builder = isset($content['content']) && $content['content'] && $content['actions'];
        if ($is_edit_layout_builder) {
            $content = $content['content'];
        }
        if (isset($content['#entity_type'])
        && ($content['#entity_type'] == "block_content"
            || $content['#entity_type'] == "inline_block")) {
             $entity = isset($content['#block_content']) ? $content['#block_content'] : $content['content']['#block_content'];
        }
        if (is_object($entity)) {
            $view_mode = isset($content['#view_mode']) ? $content['#view_mode'] : $content['content']['#view_mode'];
            $services = \Drupal::service('templating.manager');
            $output = $services->getTemplateEntity($entity, $view_mode);

            if ($output) {
                return [
                    '#type' => 'inline_template',
                    '#template' => $output,
                    '#context' => [
                        'content' => $content,
                        'entity' => $entity,
                    ],
                ];
            }

        }
        return "";
    }
    public static function render_template_node_twig($content, $entity, $view_mode = 'full')
    {
        if (is_object($entity)) {
            $services = \Drupal::service('templating.manager');
            $output = $services->getTemplateEntity($entity, $view_mode);
            if ($output) {
                return [
                    '#type' => 'inline_template',
                    '#template' => $output,
                    '#context' => [
                        'content' => $content,
                        'node' => $entity,
                    ],
                ];
            }

        }
        return "";
    }
    public static function path_templating()
    {
        $module_handler = \Drupal::service('module_handler');
        return $module_handler->getModule('templating')->getPath();
    }
    public static function spacer_top_twig($content)
    {
        if (isset($content['content']) && $content['content']['#block_content']) {
            $block = $content['content']['#block_content'];
        } else {
            $block = isset($content['#block_content']) ? ($content['#block_content']) : null;
        }
        $size = "space-empty";
        if ($block && $block->spacer && $block->spacer->value) {
            switch ($block->spacer->value) {
                case "space-tb-xs":
                    $size = "space-t-xs";
                    break;
                case "space-t-xs":
                    $size = "space-t-xs";
                    break;
                case "space-tb-sm":
                    $size = "space-t-sm";
                    break;
                case "space-t-sm":
                    $size = "space-t-sm";
                    break;
                case "space-tb-md":
                    $size = "space-t-md";
                    break;
                case "space-t-md":
                    $size = "space-t-md";
                    break;
                case "space-tb-lg":
                    $size = "space-t-lg";
                    break;
                case "space-t-lg":
                    $size = "space-t-lg";
                    break;
            }
        }
        return "<div class='spacer-mizara " . $size . "'></div>";
    }
    public static function spacer_bottom_twig($content)
    {
        if (isset($content['content']) && $content['content']['#block_content']) {
            $block = $content['content']['#block_content'];
        } else {
            $block = isset($content['#block_content']) ? ($content['#block_content']) : null;
        }
        $size = "space-empty";
        if ($block && $block->spacer && $block->spacer->value) {
            switch ($block->spacer->value) {
                case "space-tb-xs":
                    $size = "space-b-xs";
                    break;
                case "space-b-xs":
                    $size = "space-b-xs";
                    break;
                case "space-tb-sm":
                    $size = "space-b-sm";
                    break;
                case "space-b-sm":
                    $size = "space-b-sm";
                    break;
                case "space-tb-md":
                    $size = "space-b-md";
                    break;
                case "space-b-md":
                    $size = "space-b-md";
                    break;
                case "space-tb-lg":
                    $size = "space-b-lg";
                    break;
                case "space-b-lg":
                    $size = "space-b-lg";
                    break;
            }
        }
        return "<div class='spacer-mizara " . $size . "'></div>";
    }
    public static function DRUPAL_ROOT_TWIG()
    {
        return DRUPAL_ROOT;
    }
    public static function file_exists_twig($file_path)
    {
        return file_exists(DRUPAL_ROOT . '/' . $file_path);
    }

    public static function render_inline_template_twig($content)
    {
        $output = false;
        /// var_dump($content);die();
        $is_edit_layout_builder = isset($content['content']) && $content['content'] && $content['actions'];
        if ($is_edit_layout_builder) {
            $content = $content['content'];
        }

        if (isset($content['#entity_type'])
            && ($content['#entity_type'] == "block_content"
                || $content['#entity_type'] == "inline_block")) {
            $services = \Drupal::service('templating.manager');
            $config = null;
            $theme = $services->is_allowed();
            if (!$theme) {
                return false;
            }
            $activeThemeName = \Drupal::service('theme.manager')->getActiveTheme();
            $themebase = $activeThemeName->getBaseThemes();
            $base = null;
            if (!empty($themebase)) {
                $base = array_keys($themebase)[0];
            }
            $content_block = isset($content['#block_content']) ? $content['#block_content'] : $content['content']['#block_content'];
            $mode_view = isset($content['#view_mode']) ? $content['#view_mode'] : $content['content']['#view_mode'];
            $bundle = $content_block->bundle();
            $id = $content_block->id();
            $suggestion = $services->formatName('template.block--' . $theme . '-' . $bundle . "-" . $mode_view . ".html.twig");
            $suggestion_id = $services->formatName('template.block--' . $theme . '-' . $bundle . "-" . $mode_view . "-" . $id . ".html.twig");
            $output = false;
            $config_current = \Drupal::config($suggestion);
            $config_id = \Drupal::config($suggestion_id);
            $template_name = $suggestion;
            // base theme
            if ($base) {
                $suggestionbase = $services->formatName('template.block--' . $base . '-' . $bundle . "-" . $mode_view . ".html.twig");
                $config_id_base = \Drupal::config($suggestionbase);
                if ($config_id_base && $config_id_base->get('content') &&
                    $config_id_base->get('status')) {
                    $config = $config_id_base;
                }
            }
            // current theme
            if ($config_current && $config_current->get('content')) {
                $config = $config_current;

            }

            // current theme by id
            if ($config_id && $config_id->get('content')) {
                $config = $config_id;
            }

            if ($config && $config->get('content') &&
                $config->get('status')
            ) {

                $output = $config->get('content');
                $output = $services->injectionSpacer($output);
                $output = $services->assetInjection($output, $config);
            }

        }
        if ($output) {
            $element = [
                '#type' => 'inline_template',
                '#template' => $output,
                '#context' => [
                    'content' => isset($content['content']) ? $content['content'] : $content,
                ],
            ];
            return $element;
        }
        return $output;
    }
    public static function template_twig($template_name, $variables)
    {
        $suggestion_1 = "template." . $template_name;
        $config_current = \Drupal::config($suggestion_1);
        if (is_array($variables) && $config_current && $config_current->get('content')) {
            $loader = new \Twig\Loader\ArrayLoader([
                'Temp_file.html' => $config_current->get('content'),
            ]);
            $twig = new \Twig\Environment($loader);
            return $twig->render('Temp_file.html', $variables);
        } else {
            $message = 'Template   ' . $template_name . ' not exist';
            \Drupal::logger("templating")->error($message);
            return "";
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOperators()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'templating.twig.extension';
    }

}
