<?php

use MapasCulturais\i;

$nav_items = [
    'panel-menu' => [
        'label' => i::__('Menu do Painel de Controle'),
        'column' => 'left',
        'items' =>[
            ['route' => 'panel/index', 'icon' => 'dashboard', 'label' => i::__('Painel de Controle')]
        ]
    ],
   
 
    'opportunities' => [
        'label' => i::__('Editais e oportunidades'),
        'items' => []
    ],
    'main' => [
        'label' => 'Gerenciamento de entidades',
        'items' => [
            ['route' => 'panel/agents', 'icon' => 'agent', 'label' => i::__('Meus Agentes')],
            ['route' => 'panel/spaces', 'icon' => 'space', 'label' => i::__('Meus Espaços')],
            ['route' => 'panel/events', 'icon' => 'event', 'label' => i::__('Meus Eventos')],
            ['route' => 'panel/projects', 'icon' => 'project', 'label' => i::__('Meus Projetos')],
        ]
    ],

    'more' => [
        'label' => i::__('Outras opções'),
        'column' => 'right',
        'items' => [
            ['route' => 'panel/my-account', 'icon' => 'account', 'label'=> i::__('Conta e Privacidade')],
        ]
    ],

    'admin' => [
        'label' => i::__('Administração'),
        'column' => 'right',
        'condition' => function () use($app) {
            return $app->user->is('admin');
        },
        'items' => []
    ]
];

$app->applyHook('panel.nav', [&$nav_items]);

$result = [];

foreach ($nav_items as $id => $group) {
    $condition = $group['condition'] ?? function () { return true; };
    if (is_callable($condition) && $condition()) {
        unset($group['condition']);
        
        $items = [];
        foreach($group['items'] as $item) {
            $condition = $item['condition'] ?? function () { return true; };
            if (is_callable($condition) && $condition()) {
                unset($item['condition']);
                $items[] = $item;
            }
        }

        $result[] = [
            'id' => $id,
            'label' => $group['label'],
            'column' => $group['column'] ?? 'left',
            'items' => $items
        ];
    }
}

$this->jsObject['config']['panelNav'] = $result;

if ($this->activeNav ?? false) {
    $this->jsObject['activeNav'] = $this->activeNav;
}