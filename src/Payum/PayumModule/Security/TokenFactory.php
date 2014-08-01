<?php
namespace Payum\PayumModule\Security;

use Payum\Core\Security\AbstractGenericTokenFactory;
use Zend\Mvc\Controller\Plugin\Url;

class TokenFactory extends AbstractGenericTokenFactory
{
    /**
     * @var Url
     */
    protected $urlPlugin;

    /**
     * @param Url $urlPlugin
     */
    public function setUrlPlugin(Url $urlPlugin)
    {
        $this->urlPlugin = $urlPlugin;
    }

    /**
     * @param string $path
     * @param array $parameters
     *
     * @return string
     */
    protected function generateUrl($path, array $parameters = array())
    {
        return $this->urlPlugin->fromRoute($path, array(), array(
            'force_canonical' => true,
            'query' => $parameters
        ));
    }
}