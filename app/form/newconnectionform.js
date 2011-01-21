Ext.ns('monoql.form');
monoql.form.newconnectionform = function() {
	var cls = 'monoql-form-newconnectionform';
	
	var SaveButton = Ext.extend(monoql.button.button, {
		text:'Save',
		initComponent:function() {
			SaveButton.superclass.initComponent.call(this);
		}
	});
	
	var Class = Ext.extend(monoql.form.floatingform, {
		title:'Add a new connection',
		labelAlign:'left',
		width:300,
		initComponent: function() {
			this.savebutton = new SaveButton();
			this.items = [{
				fieldLabel:'Name',
				name:'name'
			},{
				fieldLabel:'Host',
				name:'host'
			},{
				fieldLabel:'Username',
				name:'username'
			},{
				fieldLabel:'Password',
				inputType:'password',
				name:'password'
			},{
				fieldLabel:'Default Database',
				name:'name'
			}];
			this.buttons = [this.savebutton];
			this.on('show', this.onNewConnectionFormShow, this);
			this.on('hide', this.onNewConnectionFormHide, this);
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onNewConnectionFormShow:function(form) {
			this.getForm().findField('name').focus();
		},
		onNewConnectionFormHide:function(form) {
			this.getForm().reset();
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();