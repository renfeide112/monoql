Ext.ns('monoql.tree');
monoql.tree.viewnode = function() {
	var cls = 'monoql-tree-viewnode';
	var Class = Ext.extend(monoql.tree.node, {
		constructor: function(attributes) {
			Ext.apply(attributes, {
				iconCls:cls + '-icon'
			});
			this.menu = new monoql.menu.viewnodemenu({
				node:this
			});
			this.menu.open.on('click', this.onMenuOpenClick, this);
			Class.superclass.constructor.call(this, attributes);
			this.attributes.cls = [this.attributes.cls, cls].join(" ");
		},
		onMenuOpenClick:function(item, e) {
			this.menu.hide();
			// TODO: This should be a viewtab, not a tabletab
			ui.tabs.addTableTab(this.getConnection(), this.getDatabase(), this.getTable());
		}
	});
	Ext.tree.TreePanel.nodeTypes[cls] = Class;
	return Class;
}();