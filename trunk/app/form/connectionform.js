Ext.ns('monoql.form');
monoql.form.connectionform = function() {
	var cls = 'monoql-form-connectionform';
	
	var SaveButton = Ext.extend(monoql.button.button, {
		text:'Save',
		formBind:true,
		initComponent:function() {
			SaveButton.superclass.initComponent.call(this);
			this.addClass(cls + "-savebutton");
		}
	});
	
	var DatabaseTypeComboBox = Ext.extend(monoql.form.combobox, {
		triggerAction:'all',
		displayField:'text',
		valueField:'type',
		forceSelection:true,
		allowBlank:false,
		mode:'local',
		fieldLabel:'Type',
		hiddenName:'type',
		value:'mysql',
		editable:true,
		initComponent:function() {
			this.store = new Ext.data.JsonStore({
				fields:['type', 'text'],
				root:'records',
				data:{"records":[
					{"type":"mysql", "text":"MySQL"}
				]}
			});
			DatabaseTypeComboBox.superclass.initComponent.call(this);
			this.addClass(cls + "-databasetypecombobox");
		}
	});
	
	var Class = Ext.extend(monoql.form.floatingform, {
		title:'Add a new connection',
		labelAlign:'left',
		width:300,
		monitorValid:true,
		hidden:true,
		renderTo:Ext.getBody(),
		constructor: function(config) {
			var config = Ext.apply({
				api:{
					load:monoql.direct.Connection.getById,
					submit:monoql.direct.Connection.formCreate
				},
				paramOrder:['id']
			}, config || {});
			Class.superclass.constructor.call(this, config);
		},
		initComponent: function() {
			this.savebutton = new SaveButton({
				form:this
			});
			this.databaseTypeComboBox = new DatabaseTypeComboBox(); 
			this.items = [{
				xtype:'hidden',
				name:'name'
			},this.databaseTypeComboBox,{
				fieldLabel:'Host',
				name:'host',
				allowBlank:false
			},{
				fieldLabel:'Username',
				name:'username',
				allowBlank:false
			},{
				fieldLabel:'Password',
				inputType:'password',
				name:'password'
			},{
				fieldLabel:'Default Database',
				name:'defaultDatabase'
			},{
				fieldLabel:'Port',
				name:'port'
			}];
			this.buttons = [this.savebutton];
			this.savebutton.on('click', this.onSaveButtonClick, this);
			this.on('show', this.onConnectionFormShow, this);
			this.on('hide', this.onConnectionFormHide, this);
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onConnectionFormShow:function(form) {
			this.getForm().findField('host').focus();
		},
		onConnectionFormHide:function(form) {
			this.getForm().reset();
		},
		onSaveButtonClick:function(button, e) {
			this.savebutton.setDisabled(true);
			var values = this.getForm().getFieldValues();
			values.name = (values.host || 'Unknown') + ' [' + (values.username || 'username') + ']';
			var conn = new monoql.data.connectionrecord(values);
			ui.connectionstore.add(conn);
			this.hide();
			this.savebutton.setDisabled(false);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();