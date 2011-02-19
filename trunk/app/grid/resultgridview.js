Ext.ns('monoql.grid');
monoql.grid.resultgridview = function() {
	var Class = Ext.extend(Ext.grid.GridView, {
		constructor:function(config) {
			config = Ext.apply({
				headersDisabled:true
			}, config);
			Class.superclass.constructor.call(this, config);
		}
	});
	return Class;
}();