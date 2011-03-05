Ext.ns('monoql.bar');
monoql.bar.querytabstatusbar = function() {
	var cls = 'monoql-bar-querytabstatusbar';
	var Class = Ext.extend(monoql.bar.toolbar, {
		initComponent: function() {
			this.connectionstatusdisplayfield = new Ext.form.DisplayField({
				value:''
			});
			this.hoststatusdisplayfield = new Ext.form.DisplayField({
				value:'Host Info...'
			});
			this.userstatusdisplayfield = new Ext.form.DisplayField({
				value:'User Info...'
			});
			this.databasestatusdisplayfield = new Ext.form.DisplayField({
				value:''
			});
			this.timerdisplayfield = new Ext.form.DisplayField({
				value:'00:00:00'
			});
			this.rowcountdisplayfield = new Ext.form.DisplayField({
				value:'0 Rows'
			});
			this.items = [this.connectionstatusdisplayfield, '->',
				'-', this.hoststatusdisplayfield,
				'-', this.userstatusdisplayfield,
				'-', this.databasestatusdisplayfield,
				'-', this.timerdisplayfield,
				'-', this.rowcountdisplayfield
			];
			this.querytab.on('connectionchange', this.onQueryTabConnectionChange, this);
			this.querytab.on('databasechange', this.onQueryTabDatabaseChange, this);
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onQueryTabConnectionChange:function(tab, oldConn, newConn) {
			this.updateConnectionStatus(newConn);
			this.updateUserStatus(newConn);
			this.updateHostStatus(newConn);
		},
		onQueryTabDatabaseChange:function(tab, oldDb, newDb) {
			this.updateDatabaseStatus(newDb);
		},
		updateConnectionStatus:function(conn) {
			var text = conn.get('name');
			this.connectionstatusdisplayfield.setValue(text);
		},
		updateUserStatus:function(conn) {
			var text = conn.get('username');
			this.userstatusdisplayfield.setValue(text);
		},
		updateHostStatus:function(conn) {
			var text = conn.get('host');
			this.hoststatusdisplayfield.setValue(text);
		},
		updateDatabaseStatus:function(database) {
			this.databasestatusdisplayfield.setValue(database || 'No Database');
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();