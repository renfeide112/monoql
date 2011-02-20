Ext.ns('monoql.tree');
monoql.tree.connectiontree = function() {
	var cls = 'monoql-tree-connectiontree';
	var Class = Ext.extend(monoql.tree.tree, {
		width:250,
		headerStyle:'border-top-width:0px;border-left-width:0px;',
		bodyStyle:'border-left-width:0px;',
		animate:false,
		root:{
			nodeType:'monoql-tree-connectiongroupnode',
			id:'root-connectiongroup',
			text:'Connections',
			expanded:false
		},
		rootVisible:false,
		useArrows:true,
		animCollapse:false,
		constructor:function(config) {
			var config = Ext.apply({
				collapseMode:'mini'
			}, config);
			Class.superclass.constructor.call(this, config);
		},
		initComponent: function() {
			this.store = ui.connectionstore;
			this.tbar = new monoql.bar.connectiontreetoolbar();
			this.loader = new monoql.tree.connectiontreeloader();
			this.tbar.refreshbutton.on('click', this.onToolbarRefreshButtonClick, this);
			this.store.on({
				scope:this,
				add:this.onStoreAdd,
				remove:this.onStoreRemove,
				update:this.onStoreUpdate
			});
			Class.superclass.initComponent.call(this);
			this.root.on('load', this.onConnectionTreeRootNodeLoad, this);
			this.getSelectionModel().on('selectionchange', this.getTopToolbar().refreshbutton.onConnectionTreeSelectionChange, this.getTopToolbar().refreshbutton);
			this.addClass(cls);
		},
		getNodeByConnection:function(connection) {
			var node;
			Ext.each(this.root.childNodes, function(item, i, items) {
				if (item.connection.id===connection.id) {
					node = item;
					return false;
				}
			});
			return node;
		},
		onConnectionTreeRootNodeLoad:function(node) {
			Ext.each(node.childNodes, function(item, i, items) {
				item.connection = ui.connectionstore.getById(item.attributes.connectionId);
			});
		},
		onToolbarRefreshButtonClick:function(button, event) {
			var selectedNode = this.getSelectionModel().getSelectedNode();
			if (selectedNode && selectedNode.reload) {
				selectedNode.reload();
			}
		},
		addConnectionNodeFromStore:function(parentNode, record) {
			var node = this.getLoader().createNode({
					text:record.data.name,
					nodeType:'monoql-tree-connectionnode'
				});
			node.connection = record;
			parentNode.appendChild(node);
		},
		onStoreAdd:function(store, records, index) {
			Ext.each(records, function(item, index, items) {
				this.addConnectionNodeFromStore(this.root, item);
			}, this);
		},
		onStoreRemove:function(store, record, index) {
			this.getNodeByConnection(record).remove(true);
		},
		onStoreUpdate:function(store, record, operation) {
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();