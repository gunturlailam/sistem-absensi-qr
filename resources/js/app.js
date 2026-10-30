import "./bootstrap";
import { Html5Qrcode, Html5QrcodeScanner } from "html5-qrcode";
import Alpine from "alpinejs";

window.Html5Qrcode = Html5QrcodeScanner;
window.Alpine = Alpine;
Alpine.start();
