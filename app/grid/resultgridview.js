Ext.ns('monoql.grid');
monoql.grid.resultgridview = function() {
	var Class = Ext.extend(Ext.ux.grid.livegrid.GridView, {
		constructor:function(config) {
			config = Ext.apply({
				nearLimit:20,
				loadMask:{
					msg:'Loading rows...'
				}
			}, config);
			Class.superclass.constructor.call(this, config);
		}
	});
	return Class;
}();