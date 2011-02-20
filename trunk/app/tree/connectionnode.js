Ext.ns('monoql.tree');
monoql.tree.connectionnode = function() {
	var cls = 'monoql-tree-connectionnode';
	var Class = Ext.extend(monoql.tree.node, {
		constructor: function(attributes) {
			Ext.apply(attributes, {
				iconCls:cls + '-icon'
			});
			this.menu = new monoql.menu.connectionnodemenu({
				node:this
			});
			this.menu.query.on('click', this.onMenuQueryClick, this);
			this.menu.modify.on('click', this.onMenuModifyClick, this);
			this.menu.remove.on('click', this.onMenuRemoveClick, this);
			Class.superclass.constructor.call(this, attributes);
			this.attributes.cls = [this.attributes.cls, cls].join(" ");
		},
		onMenuQueryClick:function(item, e) {
			this.menu.hide();
			ui.tabs.addQueryTab(this.connection);
		},
		onMenuModifyClick:function(item, e) {
			this.menu.hide();
			ui.connectionform.getForm().setValues(this.connection.data);
			var pos = Ext.get(this.ui.getEl()).getXY();
			ui.connectionform.setPosition(pos).show();
		},
		onMenuRemoveClick:function(item, e) {
			this.menu.hide();
			ui.connectionstore.remove(this.connection);
		}
	});
	Ext.tree.TreePanel.nodeTypes[cls] = Class;
	return Class;
}();