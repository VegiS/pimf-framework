<?php
/**
 * View
 *
 * @copyright Copyright (c)  Gjero Krsteski (http://krsteski.de)
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace Pimf\View;

use Pimf\Contracts\Reunitable;
use Pimf\View;
use Pimf\Config;


/**
 * A view for TWIG a flexible, fast, and secure template engine for PHP.
 *
 * For use please add the following code to the end of the config.app.php file:
 *
 * <code>
 *
 * 'view' => array(
 *
 *   'twig' => array(
 *     'cache'       => true,  // if compilation caching should be used
 *     'debug'       => false, // if set to true, you can display the generated nodes
 *     'auto_reload' => true,  // useful to recompile the template whenever the source code changes
 *  ),
 *
 * ),
 *
 * </code>
 *
 * @link    http://twig.sensiolabs.org/documentation
 * @package View
 * @author  Gjero Krsteski <gjero@krsteski.de>
 * @codeCoverageIgnore
 */
class Twig extends View implements Reunitable
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @param string $template
     * @param array  $data
     */
    public function __construct($template, array $data = array())
    {
        parent::__construct($template, $data);

        $conf = Config::get('view.twig');

        require_once BASE_PATH . "Twig/vendor/autoload.php";

        $options = array(
            'debug'       => $conf['debug'],
            'auto_reload' => $conf['auto_reload']
        );

        if ($conf['cache'] === true) {
            $options['cache'] = $this->path . '/twig_cache';
        }

        // define the Twig environment.
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem(array($this->path)), $options);
    }

    /**
     * @return \Twig_Environment
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * Puts the template an the variables together.
     *
     * @return string|void
     */
    public function reunite()
    {
        return $this->twig->render(
            $this->template, $this->data->getArrayCopy()
        );
    }
}
