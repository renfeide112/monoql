Ext.ns('monoql.tree');
monoql.tree.tablenode = function() {
	var cls = 'monoql-tree-tablenode';
	var Class = Ext.extend(monoql.tree.node, {
		constructor: function(attributes) {
			Ext.apply(attributes, {
				iconCls:cls + '-icon'
			});
			this.menu = new monoql.menu.tablenodemenu({
				node:this
			});
			this.menu.open.on('click', this.onMenuOpenClick, this);
			this.menu.query.on('click', this.onMenuQueryClick, this);
			Class.superclass.constructor.call(this, attributes);
			this.on('beforeload', this.onTableNodeBeforeLoad, this);
			this.attributes.cls = [this.attributes.cls, cls].join(" ");
		},
		onMenuQueryClick:function(item, e) {
			this.menu.hide();
			ui.tabs.addQueryTab(this.getConnection(), this.getDatabase());
		},
		onTableNodeBeforeLoad:function(node) {
			Ext.apply(node.getOwnerTree().getLoader().baseParams, {
				table:node.getTable(),
				database:node.getDatabase()
			});
		},
		onMenuOpenClick:function(item, e) {
			this.menu.hide();
			ui.tabs.addTableTab(this.getConnection(), this.getDatabase(), this.getTable());
		}
	});
	Ext.tree.TreePanel.nodeTypes[cls] = Class;
	return Class;
}();