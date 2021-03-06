Planck.Extension.EntityEditor.Module.Entity.Controller.EntityManager = function(container)
{

    this.$container = $(container);
    this.$entityEditorContainer = this.$container.find('.plk-entity-editor-container');


    this.$entityListContainer = this.$container.find('.plk-entity-list-container');

    this.entityList;
    this.entityEditor;
};




Planck.Extension.EntityEditor.Module.Entity.Controller.EntityManager.prototype.initialize = function()
{

    this.entityList = new Planck.Extension.EntityEditor.View.Component.EntityList(this.$entityListContainer);

    this.entityList.on('itemClick', function(entityDescriptor) {
        this.loadEditorByEntityDescriptor(entityDescriptor);

        //this.renderEntityEditor();

    }.bind(this));


    this.entityList.load();


};

Planck.Extension.EntityEditor.Module.Entity.Controller.EntityManager.prototype.loadEditorByEntityDescriptor = function(descriptor)
{


    this.entityEditor = new Planck.Extension.EntityEditor.View.Component.EntityEditor(this.$entityEditorContainer);
    this.entityEditor.setEntity(descriptor.entity);
    this.entityEditor.load();

};





