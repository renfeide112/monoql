Ext.override(Ext.data.Store, {
	onCreateRecords : function(success, rs, data) {
		if (success === true) {
			try {
				/* REPLACE EACH ARGUMENT TO this.reader.realize WITH A COPY OF THE ARRAY */
				/* ORIGINAL */ //this.reader.realize(rs, data);
				/* OVERRIDE */ this.reader.realize([].concat(rs), [].concat(data));
				this.reMap(rs);
			}
			catch (e) {
				this.handleException(e);
				if (Ext.isArray(rs)) {
					// Recurse to run back into the try {}.  DataReader#realize splices-off the rs until empty.
					this.onCreateRecords(success, rs, data);
				}
			}
		}
	}
});