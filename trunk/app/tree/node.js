Ext.ns('monoql.tree');
monoql.tree.node = function() {
	var cls = 'monoql-tree-node';
	var Class = Ext.extend(Ext.tree.AsyncTreeNode, {
		constructor: function(attributes) {
			Class.superclass.constructor.call(this, attributes);
			this.attributes.cls = [this.attributes.cls, cls].join(" ");
			if (this.menu && this.menu.refresh) {
				this.menu.refresh.on('click', this.onMenuRefreshClick, this);
			}
			this.on('contextmenu', this.onNodeContextMenu, this);
			this.on('collapse', this.onNodeCollapse, this);
		},
		onMenuRefreshClick:function(item, event) {
			if (Ext.isFunction(this.reload)) {
				this.reload();
			}
		},
		onNodeContextMenu:function(node, event) {
			node.select();
			if (node.menu && node.menu.items) {
				node.menu.showAt(event.getXY());
			}
		},
		getConnection:function() {
			var node = this;
			while (node) {
				if (node.connection) {
					return node.connection;
				} else {
					node = node.parentNode;
				}
			}
			return null;
		},
		getDatabase:function() {
			var node = this;
			while (node) {
				if (node.attributes.database) {
					return node.attributes.database;
				} else {
					node = node.parentNode;
				}
			}
			return null;
		},
		getTable:function() {
			var node = this;
			while (node) {
				if (node.attributes.table) {
					return node.attributes.table;
				} else {
					node = node.parentNode;
				}
			}
			return null;
		},
		onNodeCollapse:function(node) {
			(function() {
				while (this.firstChild) {
					this.removeChild(this.firstChild).destroy();
				}
				this.childrenRendered = false;
				this.loaded = false;
				if (this.isHiddenRoot()) {
					this.expanded = false;
				}
				this.ui.updateExpandIcon();
			}).createDelegate(this).defer(100);
		}
	});
	Ext.tree.TreePanel.nodeTypes[cls] = Class;
	return Class;
}();