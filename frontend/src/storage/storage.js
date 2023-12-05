const TOKEN = "token";

const storageGetItem = (key) => {
  return localStorage.getItem(key);
};

const storageSetItem = (key, value) => {
  return localStorage.setItem(key, value);
};

const storageKeyExists = (key) => {
  return storageGetItem(key) !== "";
};

export { TOKEN, storageKeyExists, storageGetItem, storageSetItem };