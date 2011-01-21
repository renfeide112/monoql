Ext.ns('monoql.tree');
monoql.tree.connectiontreeloader = function() {
	var Class = Ext.extend(Ext.tree.TreeLoader, {
		constructor:function(config){
			config = Ext.apply({
				url:monoql.url('/_services/connection/getchildnodes'),
				baseParams:{
					format:'json'
				}
			}, config || {});
			Class.superclass.constructor.call(this, config);
			this.addListener('beforeload', this.onLoaderBeforeLoad, this);
		},
		onLoaderBeforeLoad:function(loader, node, callback) {
			var nodeType = node.attributes.nodeType.substr(node.attributes.nodeType.lastIndexOf('-')+1);
			loader.url = monoql.url('/_services/connection/get' + nodeType + 'children');
			loader.baseParams.nodeType = node.attributes.nodeType;
		}
	});
	return Class;
}();