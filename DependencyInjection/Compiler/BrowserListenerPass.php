<?php

namespace Playbloom\Bundle\BuzzBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Browser listener pass
 *
 * Pass responsible to add listener to every tagged buzz browser
 *
 * @author Ludovic Fleury <ludo.fleury@gmail.com>
 */
class BrowserListenerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $clients = $container->findTaggedServiceIds('playbloom_buzz.browser');

        if (empty($clients)) {
            return;
        }

        $listeners = $container->findTaggedServiceIds('playbloom_buzz.browser.listener');

        foreach ($clients as $clientId => $attribute) {
            $browserDefinition = $container->findDefinition($clientId);

            $this->registerBuzzListener($browserDefinition, $listeners);

            if ($container->hasDefinition('profiler')) {
                $browserDefinition->addMethodCall(
                    'addListener',
                    array(new Reference('playbloom_buzz.browser.listener.profiler'))
                );
            }
        }
    }

    /**
     * Register listeners for a browser
     *
     * @param  Definition $browserDefinition
     * @param  array  $listeners
     */
    private function registerBuzzListener(Definition $browserDefinition, array $listeners)
    {
        foreach ($listeners as $listenerId => $attributes) {
            $browserDefinition->addMethodCall(
                'addListener',
                array(new Reference($listenerId))
            );
        }
    }
}
