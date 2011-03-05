Ext.ns('monoql.data');
monoql.data.tablegridproxy = Ext.extend(Ext.data.DirectProxy, {
	constructor:function(config) {
		var config = Ext.apply({
			api:{
				read:monoql.direct.TableGrid.load
			},
			paramOrder:['table', 'connectionId', 'limit', 'start', 'sort', 'dir', 'database']
		}, config);
		monoql.data.tablegridproxy.superclass.constructor.call(this, config);
	}
});

monoql.data.tablegridrecord = Ext.data.Record.create([
	// Fields will defined at runtime by the column list for the recordset returned by the query
]);

monoql.data.tablegridreader = Ext.extend(Ext.ux.grid.livegrid.JsonReader, {
	constructor:function(meta, recordType) {
		meta = Ext.apply({
			idProperty:'__id__',
			root:'rows',
			totalProperty:'total',
			successProperty:'success',
			messageProperty:'message',
			fields:monoql.data.tablegridrecord
		}, meta);
		monoql.data.tablegridreader.superclass.constructor.call(this, recordType);
	}
});

monoql.data.tablegridstore = function() {
	var Class = Ext.extend(Ext.ux.grid.livegrid.Store, {
		constructor:function(config) {
			config = Ext.apply({
				bufferSize:100,
				autoDestroy:true,
				autoLoad:false,
				remoteSort:true,
				sortInfo:{field:null, direction:null},
				proxy:new monoql.data.tablegridproxy({store:this}),
				reader:new monoql.data.tablegridreader({store:this}),
				baseParams:{
					start:0,
					limit:100,
					sort:null,
					dir:null
				}
			}, config);
			Class.superclass.constructor.call(this, config);
		}
	});
	return Class;
}();