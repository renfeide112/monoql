Ext.ns('monoql.data');
monoql.data.resultgridreader = function() {
	var Class = Ext.extend(Ext.data.JsonReader, {
		constructor:function(meta, recordType) {
			meta = Ext.apply({
				idProperty:'id',
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