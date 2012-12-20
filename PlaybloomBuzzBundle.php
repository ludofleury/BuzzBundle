<?php

namespace Playbloom\Bundle\BuzzBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Playbloom\Bundle\BuzzBundle\DependencyInjection\Compiler\BrowserListenerPass;

/**
 * Playbloom Buzz Bundle
 *
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class PlaybloomBuzzBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new BrowserListenerPass());
    }
}
