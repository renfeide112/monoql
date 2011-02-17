Ext.ns('monoql.form');
monoql.form.addquerytabform = function() {
	var cls = 'monoql-form-addquerytabform';
	
	var OpenTabButton = Ext.extend(monoql.button.button, {
		text:'Open Tab',
		initComponent:function() {
			OpenTabButton.superclass.initComponent.call(this);
		}
	});
	
	var Class = Ext.extend(monoql.form.floatingform, {
		title:'Add a new query tab',
		width:200,
		initComponent: function() {
			this.opentabbutton = new OpenTabButton({
				disabled:true
			});
			this.connectioncombobox = new monoql.form.connectioncombobox({
				fieldLabel:'Choose a connection'
			});
			this.connectioncombobox.on('select', this.onConnectionComboBoxSelect, this);
			this.items = [this.connectioncombobox];
			this.buttons = [this.opentabbutton];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onConnectionComboBoxSelect:function(combo, record, index) {
			var value = combo.getValue(),
				valid = Ext.isNumber(value) && value>0
			this.opentabbutton.setDisabled(!valid);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();