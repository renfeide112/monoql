Ext.ns('monoql.tree');
monoql.tree.connectiontree = function() {
	var cls = 'monoql-tree-connectiontree';
	var Class = Ext.extend(monoql.tree.tree, {
		width:250,
		//title:'Data Connections',
		headerStyle:'border-top-width:0px;border-left-width:0px;',
		bodyStyle:'border-left-width:0px;',
		animate:false,
		root:{
			nodeType:'monoql-tree-connectiongroupnode',
			id:'root-connectiongroup',
			text:'Connections'
		},
		rootVisible:false,
		useArrows:true,
		initComponent: function() {
			this.tbar = new monoql.bar.connectiontreetoolbar();
			this.loader = new monoql.tree.connectiontreeloader();
			this.tbar.refreshbutton.addListener('click', this.onToolbarRefreshButtonClick, this);
			Class.superclass.initComponent.call(this);
			this.getSelectionModel().on('selectionchange', this.getTopToolbar().refreshbutton.onConnectionTreeSelectionChange, this.getTopToolbar().refreshbutton);
			this.addClass(cls);
		},
		onToolbarRefreshButtonClick:function(button, event) {
			var selectedNode = this.getSelectionModel().getSelectedNode();
			if (selectedNode && selectedNode.reload) {
				selectedNode.reload();
			}
		},
		addNodeFromStore:function(parentNode, record) {
			var node = this.getLoader().createNode(Ext.apply(record.data, {
				text:record.data.name,
				nodeType:'monoql-tree-connectionnode'
			}));
			parentNode.appendChild(node);
		},
		initListeners:function() {
			ui.connectionstore.on('add', this.onConnectionStoreAdd, this);
			ui.connectionstore.on('remove', this.onConnectionStoreRemove, this);
			ui.connectionstore.on('update', this.onConnectionStoreUpdate, this);
		},
		onConnectionStoreAdd:function(store, records, index) {
			Ext.each(records, function(item, index, items) {
				this.addNodeFromStore(this.root, item);
			}, this);
		},
		onConnectionStoreRemove:function(store, record, index) {
		},
		onConnectionStoreUpdate:function(store, record, operation) {
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();