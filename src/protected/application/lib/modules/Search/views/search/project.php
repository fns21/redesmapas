<?php 
use MapasCulturais\i;
 
$this->import('search tabs search-list search-map search-filter-project');
$this->breadcramb = [
    ['label'=> i::__('Inicio'), 'url' => $app->createUrl('index')],
    ['label'=> i::__('Projetos'), 'url' => $app->createUrl('projects')],
];
?>

<search page-title="<?php i::esc_attr_e('Projetos') ?>" entity-type="project" >    
    <template #create-button>
        <!-- @TODO: Criação e aplicação do componente <create-project> -->
        <?= i::_e('botão criar projeto') ?>
    </template>
    <template #default="{pseudoQuery}">
        <div class="tabs-component__panels">
            <div class="search__tabs--list">
                <search-list :pseudo-query="pseudoQuery" type="project">
                    <template #filter>
                        <search-filter-project :pseudo-query="pseudoQuery"></search-filter-project>
                    </template>
                </search-list>
            </div>
        </div>
    </template>
</search>