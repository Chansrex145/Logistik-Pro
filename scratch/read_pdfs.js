const fs = require('fs');
const path = require('path');
const PDFParser = require('pdf2json');

const pdfDir = 'c:\\xampp\\htdocs\\logistikapp\\Proposal';
const files = [
    '160422108_BAB 1 (1).pdf',
    '160422108_BAB 2 (1).pdf',
    '160422108_BAB 3 (1).pdf',
    'revisi proposal.pdf'
];

async function extractTexts() {
    for (const file of files) {
        const filePath = path.join(pdfDir, file);
        await new Promise((resolve, reject) => {
            const pdfParser = new PDFParser(this, 1);
            
            pdfParser.on("pdfParser_dataError", errData => {
                console.error(errData.parserError);
                reject(errData.parserError);
            });
            
            pdfParser.on("pdfParser_dataReady", pdfData => {
                const outPath = path.join(__dirname, file.replace('.pdf', '.txt'));
                fs.writeFileSync(outPath, pdfParser.getRawTextContent());
                console.log(`Extracted: ${file} to ${outPath}`);
                resolve();
            });
            
            pdfParser.loadPDF(filePath);
        });
    }
}

extractTexts().catch(console.error);
