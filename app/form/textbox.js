Ext.ns('monoql.form');
monoql.form.textbox = function() {
	var cls = 'monoql-form-textbox';
	var Class = Ext.extend(Ext.form.TextField, {
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();

Ext.apply(Ext.form.TextField.prototype, {
	getSelectedText:function() {
		var selection = this.getSelection();
		return selection ? selection.text : null;
	},
	getSelection:function() {
		var domElement = this.getEl().dom; 
		if (Ext.isIE){ 
			var sel = document.selection;
			var range = sel.createRange();
			if (range.parentElement()!=domElement) return null;
			var bookmark = range.getBookmark();
			var selection = domElement.createTextRange();
			selection.moveToBookmark(bookmark);
			var before = domElement.createTextRange();
			before.collapse(true);
			before.setEndPoint("EndToStart", selection);
			var after = domElement.createTextRange();
			after.setEndPoint("StartToEnd", selection);
			return {
				selectionStart: before.text.length,
				selectionEnd: before.text.length + selection.text.length,
				beforeText: before.text,
				text: selection.text,
				afterText: after.text
			};
		} else {
			if (domElement.selectionEnd && domElement.selectionStart) {
				if (domElement.selectionEnd > domElement.selectionStart){ 
					return {
						selectionStart  : domElement.selectionStart,
						selectionEnd : domElement.selectionEnd,
						beforeText   : domElement.value.substr(0, domElement.selectionStart),
						text    : domElement.value.substr(domElement.selectionStart, domElement.selectionEnd - domElement.selectionStart),
						afterText   : domElement.value.substr(domElement.selectionEnd)
					};
				} 
			}
		}
		return null;
	}
});