# SafeSites 🚀  
An open-source system for automatically blocking harmful websites, helping users avoid addictions and browse the internet safely.

---

## 🛠️ Features  
✅ Generates custom `hosts` files  

---

## 🚀 Installation  
### 1. Clone the repository  
```bash
git clone https://github.com/BranDavidIonel/safeSites.git
cd safehosts
```

### 2. Install dependencies  
```bash
composer install
```

### 3. Set up environment variables  
Copy the example `.env` file and modify it if needed:
```bash
cp .env.example .env
```

### 4. Start Laravel Sail (if using Docker)  
```bash
./vendor/bin/sail up -d
```

### 5. Run database migrations  
```bash
php artisan migrate
```

---

## 🖥️ Custom Artisan Commands  
SafeHosts provides custom Artisan commands for managing blocklists and generating host files. Below is a list of available commands:

### **1. Add a Custom Domain to Blocklist**  
```bash
php artisan hosts:add-custom
```
- **Description:** Manually add domains to the user’s custom blocklist.

---

### **2. Generate a Hosts File**  
```bash
php artisan hosts:generate-file
```
- **Description:** Generates a `hosts` file containing blocked domains from various sources.
- **After running the command, manually update the system hosts file:**
  ```bash
  sudo cp ./storage/app/custom_hosts.txt /etc/hosts
  ```

---

### **3. Import Blocked Hosts from a URL**  
```bash
php artisan hosts:import --url=https://example.com/blocklist.txt
```
- **Description:** Imports a list of blocked hosts from a specified URL.

---

## 📚 Documentation  
I will make..

---

## 🌐 Contributing  
We welcome contributions! Feel free to submit a pull request or open an issue.

---

## 🏆 License  
This project is open-source and available under the **MIT License**.

---

## 📢 Support  
If you find this project useful, consider giving it a ⭐ on GitHub!


