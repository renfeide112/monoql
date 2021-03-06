Ext.ns('monoql.data');
monoql.data.resultgridproxy = function() {
	var Class = Ext.extend(Ext.data.DirectProxy, {
		constructor:function(config) {
			var config = Ext.apply({
				api:{
					read:monoql.direct.ResultGrid.load
				},
				paramOrder:['query', 'connectionId', 'limit', 'start', 'database']
			}, config);
			Class.superclass.constructor.call(this, config);
		}
	});
	return Class;
}();