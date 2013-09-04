var TreeItem = Backbone.DeepModel.extend({
    defaults: {
        name: '', //optional function that is called when item clicked
        link: '',
        route: '',
        idAttribute: '',
        classAttribute: '',
        title: '',
        items: undefined,
        parent: undefined
    }
   
});

var Tree = Backbone.Collection.extend({
        model: TreeItem,
});

TreeView = Backbone.View.extend({
        template: _.template($("#menu_list").html()),
        tagName: "ul",
        events: {
            "click a[name=add_child_item]" : "addChildItem",
            "click a[name=add_item]" : "addItem",
            "click a[name=delete_item]" : "deleteItem",
            "click a[name=edit_item]" : "editItem"
        },
        initialize: function(){
            this.collection = new Backbone.Collection;
            this.collection.model = TreeItem;
        },
        render: function(){
            $(this.el).html(this.template({
                menu: this.collection
            }));
            $("#menu_list").find(this.tagName).remove();
            $("#menu_list").append(this.el);
        },
        addItem: function(event){
            var  formView = new TreeItemView();
            var Item = new TreeItem();
            formView.model = Item;
            formView.render(); 
        },
        addChildItem: function(event){
            var  formView = new TreeItemView();
            var Item = new TreeItem();
            Item.parent = event.currentTarget.id;
            formView.model = Item;
            formView.render(); 
        },     
        editChildItem: function(event){
            var  formView = new TreeItemView(); 
            formView.model = this.collection.get(event.currentTarget.id);
            formView.render(); 
        },
        deleteChildItem: function(event){
            if(confirm("Are you sure?"))
            {
                var  formView = new TreeItemView(); 
                formView.model = this.collection.get(event.currentTarget.id);
                formView.destroy({
                    success:  function(){
                        treeView = new TreeView();
                        treeView.render();
                    }
                });
            }
        } 
}); 

var TreeItemView = Backbone.View.extend({
    template: _.template($("#add_menu_item").html()),
    events: {
         "click a[name=save_item]" : "saveItem",
         "click a[name=cancel_add]" : "cancel",
    },
    initialize: function(){
         this.collection = new Backbone.Collection;
         this.collection.model = TreeItem;
    },
    saveItem: function(){
       this.model.set({
            name: '', //optional function that is called when item clicked
            link: '',
            route: '',
            idAttribute: '',
            classAttribute: '',
            title: '',
            items: undefined,
            parent: undefined    
       });
       if (this.model.isNew()) {
                this.collection.create(this.model, {
                    success: function(response)
                    {
                        
                    }    
                });
            } else {
                this.model.save({}, {
                    success: function(response){
                        
                        
                    }
                });
         }
    },
    cancel: function(){
        
    },
    
});


