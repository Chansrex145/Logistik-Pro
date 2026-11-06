const fs = require('fs');
const PDFParser = require('pdf2json');
const pdfParser = new PDFParser(this, 1);
pdfParser.on('pdfParser_dataError', errData => console.error(errData.parserError));
pdfParser.on('pdfParser_dataReady', pdfData => {
    fs.writeFileSync('c:/xampp/htdocs/logistikapp/scratch/desain_ui_4_3.txt', pdfParser.getRawTextContent());
    console.log('Done');
});
pdfParser.loadPDF('c:/xampp/htdocs/logistikapp/Referensi/4.3. Desain Tampilan Antar Muka.pdf');
