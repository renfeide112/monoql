Ext.ns('monoql.tab');
monoql.tab.maintabset = function() {
	var cls = 'monoql-tab-maintabset';
	var queryTabCount = 0;
	var Class = Ext.extend(monoql.tab.tabset, {
		headerStyle:'border-top-width:0px;',
		tabWidth:150,
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		addQueryTab:function(connection, database) {
			if (connection) {
				var tab = new monoql.tab.querytab({
					index:++queryTabCount
				});
				tab.setConnection(connection);
				if (database) {
					tab.setDatabase(database);
				}
				this.activate(this.add(tab));
				return tab;
			}
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();