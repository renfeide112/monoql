Ext.ns('monoql.data');
monoql.data.resultgridrecord = function() {
	var Class = Ext.data.Record.create([
		// Fields will defined at runtime by the column list for the recordset returned by the query
	]);
	return Class;
}();