Ext.ns('monoql.data');
monoql.data.connectionstore = function() {
	var Record = Ext.data.Record.create([
		{name:'id', type:'string'},
		{name:'name', type:'string'},
		{name:'description', type:'string'},
		{name:'type', type:'string'},
		{name:'host', type:'string'},
		{name:'username', type:'string'},
		{name:'port', type:'int'},
		{name:'database', type:'string'}
	]);
	
	var Reader = Ext.extend(Ext.data.JsonReader, {
		constructor:function(meta, recordType) {
			meta = Ext.apply({
				root:'records'
			}, meta);
			Reader.superclass.constructor.call(this, meta, Record);
		}
	});
	
	var Writer = Ext.extend(Ext.data.JsonWriter, {
		constructor:function(meta, recordType) {
			meta = Ext.apply({
				encode:false,
				listful:true
			}, meta);
			Writer.superclass.constructor.call(this, meta, Record);
		}
	});
	
	var Proxy = Ext.extend(Ext.data.DirectProxy, {
		constructor:function(config) {
			var config = Ext.apply({
				api:{
					read:monoql.direct.Connection.get,
					create:monoql.direct.Connection.create,
					update:monoql.direct.Connection.save,
					destroy:monoql.direct.Connection.delete
				}
			}, config);
			Proxy.superclass.constructor.call(this, config);
		}
	});

	var Class = Ext.extend(Ext.data.Store, {
		constructor:function(config) {
			config = Ext.apply({
				proxy:new Proxy(),
				reader:new Reader(),
				writer:new Writer()
			}, config);
			Class.superclass.constructor.call(this, config);
		}
	});
	return Class;
}();