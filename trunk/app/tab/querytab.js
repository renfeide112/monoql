Ext.ns('monoql.tab');
monoql.tab.querytab = function() {
	var cls = 'monoql-tab-querytab';
	var Class = Ext.extend(monoql.tab.tab, {
		title:'Query',
		layout:'border',
		border:false,
		closable:true,
		connection:null,
		database:null,
		constructor:function(config) {
			this.addEvents('connectionchange', 'databasechange');
			Class.superclass.constructor.call(this, config);
		},
		initComponent: function() {
			this.updateTitle();
			this.bbar = new monoql.bar.querytabstatusbar({
				querytab:this
			});
			this.queryform = new monoql.form.queryform({
				region:'north',
				tab:this,
				height:200,
				split:true
			});
			this.resulttabset = new monoql.tab.resulttabset({
				region:'center',
				split:true,
				border:false,
				bodyStyle:'border-top-width:1px;'
			});
			ui.toolbar.connectioncombobox.on('select', this.onToolBarConnectionComboBoxSelect, this);
			this.queryform.getForm().on('actioncomplete', this.onQueryFormActionComplete, this);
			this.on('connectionchange', this.onConnectionChange, this);
			this.items = [this.queryform, this.resulttabset];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onConnectionChange:function(tab, oldConn, newConn) {
			this.updateTitle();
			this.setDatabase();
		},
		updateTitle:function() {
			var title = 'Query' + (Ext.isNumber(this.index) ? ' ' + this.index : '');
			title = title + (this.connection ? ' [' + this.connection.get('host') + ']' : '');
			this.setTitle(title);
			return title;
		},
		onQueryFormActionComplete:function(form, action) {
			if (action.type=="submit") {
				this.onQueryFormSubmitComplete(form, action);
			}
		},
		onQueryFormSubmitComplete:function(form, action) {
			alert(Ext.pluck(action.result.rows, "username"));
		},
		isActive:function() {
			return this.ownerCt && this.ownerCt.getActiveTab()==this;
		},
		setConnection:function(connection) {
			var old = this.connection;
			this.connection = Ext.isObject(connection) ? connection : ui.connectionstore.getById(connection);
			this.fireEvent('connectionchange', this, old, this.connection);
			return this.connection;
		},
		setDatabase:function(database) {
			var old = this.database;
			this.database = database;
			this.fireEvent('databasechange', this, old, this.database);
			return this.database;
		},
		onToolBarConnectionComboBoxSelect:function(combo, record, index) {
			if (this.isActive()) {
				this.setConnection(combo.getValue());
			}
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();