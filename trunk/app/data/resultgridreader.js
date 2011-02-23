Ext.ns('monoql.data');
monoql.data.resultgridreader = function() {
	var Class = Ext.extend(Ext.ux.grid.livegrid.JsonReader, {
		constructor:function(meta, recordType) {
			meta = Ext.apply({
				idProperty:'__id__',
				root:'rows',
				totalProperty:'total',
				successProperty:'success',
				messageProperty:'message',
				fields:monoql.data.resultgridrecord
			}, meta);
			Class.superclass.constructor.call(this, recordType);
		}
	});
	return Class;
}();