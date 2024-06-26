<?php

use MapasCulturais\ApiQuery;
use MapasCulturais\App;

$app = App::i();

$cards = $app->config['Metabase']['config']['cards']['entities'];

$app->applyHook('component(home-metabase).data', [&$cards]);

foreach ($cards as &$card) {
    foreach ($card['data'] as &$data) {
        $query = $data['query'];
        $entity = $data['entity'];
        $api_query = new ApiQuery($entity, $query);
        $data['data'] = $api_query->getFindResult();
        $data['value'] = $api_query->getCountResult();
    }
}

$this->jsObject['config']['homeMetabase'] = $cards;

return;

