Ext.ns('monoql.tree');
monoql.tree.connectiontreeloader = function() {
	var Class = Ext.extend(Ext.tree.TreeLoader, {
		constructor:function(config){
			config = Ext.apply({
				directFn:monoql.direct.ConnectionTree.getChildNodes
			}, config || {});
			Class.superclass.constructor.call(this, config);
			this.addListener('beforeload', this.onLoaderBeforeLoad, this);
		},
		onLoaderBeforeLoad:function(loader, node, callback) {
			loader.baseParams.nodeType = node.attributes.nodeType;
		}
	});
	return Class;
}();