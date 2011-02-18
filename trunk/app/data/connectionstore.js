Ext.ns('monoql.data');
monoql.data.connectionstore = function() {	
	var Reader = Ext.extend(Ext.data.JsonReader, {
		constructor:function(meta, recordType) {
			meta = Ext.apply({
				root:'records'
			}, meta);
			Reader.superclass.constructor.call(this, meta, recordType);
		}
	});
	
	var Writer = Ext.extend(Ext.data.JsonWriter, {
		constructor:function(meta, recordType) {
			meta = Ext.apply({
				encode:false,
				listful:true
			}, meta);
			Writer.superclass.constructor.call(this, meta, recordType);
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
				sortInfo:{field:'name', dir:'ASC'},
				proxy:new Proxy(),
				reader:new Reader({}, monoql.data.connectionrecord),
				writer:new Writer({}, monoql.data.connectionrecord)
			}, config);
			Class.superclass.constructor.call(this, config);
		}
	});
	return Class;
}();