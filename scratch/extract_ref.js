const fs = require('fs');
const PDFParser = require('pdf2json');
const pdfParser = new PDFParser(this, 1);
pdfParser.on('pdfParser_dataError', errData => console.error(errData.parserError));
pdfParser.on('pdfParser_dataReady', pdfData => {
    fs.writeFileSync('c:/xampp/htdocs/logistikapp/scratch/referensi_bab4.txt', pdfParser.getRawTextContent());
    console.log('Done');
});
pdfParser.loadPDF('c:/xampp/htdocs/logistikapp/Referensi/TA_160422163.docx.pdf');
