import CryptoJS from "crypto-js";

const SECRET_KEY = import.meta.env.CYRYPTO_SECRET_KEY || "fallback_secret_key"; // Change this for production!

// 🔐 Securely Store Data
export function saveToLocalStorage(key: string, data: any) {
  try {
    if (!data || typeof data !== "object") {
      console.error("Invalid data to store:", data);
      return;
    }

    const jsonData = JSON.stringify(data);
    const encryptedData = CryptoJS.AES.encrypt(jsonData, SECRET_KEY).toString();
    localStorage.setItem(key, encryptedData);
  } catch (error) {
    console.error("Error saving to local storage:", error);
  }
}

// 🔓 Retrieve & Decrypt Data
export function getFromLocalStorage(key: string) {
  try {
    const encryptedData = localStorage.getItem(key);
    if (!encryptedData) return null;

    const bytes = CryptoJS.AES.decrypt(encryptedData, SECRET_KEY);
    const decryptedData = bytes.toString(CryptoJS.enc.Utf8);

    if (!decryptedData) {
      console.error("Decryption failed for key:", key);
      return null;
    }

    return JSON.parse(decryptedData);
  } catch (error) {
    console.error("Error reading from local storage:", error);
    return null;
  }
}
