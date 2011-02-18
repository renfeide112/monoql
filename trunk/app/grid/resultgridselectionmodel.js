Ext.ns('monoql.grid');
monoql.grid.resultgridselectionmodel = function() {
	var Class = Ext.extend(Ext.grid.RowSelectionModel, {
		constructor:function(config) {
			config = Ext.apply({
			}, config);
			Class.superclass.constructor.call(this, config);
		}
	});
	return Class;
}();