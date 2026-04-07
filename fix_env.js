const fs = require('fs');
let env = fs.readFileSync('.env');
let clean = '';
for (let i = 0; i < env.length; i++) {
    if (env[i] !== 0) {
        clean += String.fromCharCode(env[i]);
    }
}
clean = clean.replace(/VAPID_PUBLIC_KEY.*$/gm, '').replace(/VAPID_PRIVATE_KEY.*$/gm, '') + 
"\nVAPID_PUBLIC_KEY=BHW1zL1Bv9uHG21URNZdcGhaBNOw-47iXwZhRAjS51Hl21pwXmouxkGdn3JGpGJvp2WAdnGH_mkDNg6wKurD6s8" +
"\nVAPID_PRIVATE_KEY=40xJWv9zPVA8OrWcFHMYt_kTmAhwfgRHpdwIxxvT518\n";
fs.writeFileSync('.env', clean);
