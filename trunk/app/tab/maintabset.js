Ext.ns('monoql.tab');
monoql.tab.maintabset = function() {
	var cls = 'monoql-tab-maintabset';
	var queryTabCount = 0;
	var Class = Ext.extend(monoql.tab.tabset, {
		headerStyle:'border-top-width:0px;',
		initComponent: function() {
			this.items = [];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		addQueryTab:function(connection) {
			if (connection) {
				var tab = new monoql.tab.querytab({
					index:++queryTabCount
				});
				tab.setConnection(connection);
				this.activate(this.add(tab));
				return tab;
			}
		},
		initListeners:function() {
			ui.addquerytabform.opentabbutton.on('click', this.onAddQueryTabFormOpenTabButtonClick, this);
		},
		onAddQueryTabFormOpenTabButtonClick:function(button, e) {
			var combo = ui.addquerytabform.connectioncombobox,
				value = combo.getValue(),
				valid = Ext.isNumber(value) && value>0
			if (valid) {
				ui.addquerytabform.hide();
				ui.addquerytabform.opentabbutton.setDisabled(true);
				combo.setValue();
				var connection = ui.connectionstore.getById(value);
				this.addQueryTab(connection);
			}
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();