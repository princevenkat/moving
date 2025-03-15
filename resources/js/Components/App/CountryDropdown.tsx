import React, {useState, useEffect} from 'react';
import axios from 'axios';
import 'daisyui/dist/full.css';

interface Country {
  code: string;
  name: string;
  flag: string;
}

interface CountryDropdownProps {
  selectedCountry: string,
  onCountryChange: (countryCode: string) => void,
  disabled?: boolean
}


;

const CountryDropdown: React.FC<CountryDropdownProps> = ({selectedCountry, onCountryChange, disabled}) => {
  const [countries, setCountries] = useState<Country[]>([]);
  const [isOpen, setIsOpen] = useState<boolean>(false);
  const [userCountryCode, setUserCountryCode] = useState<string>('');

  //const $ipInfoApi = process.env.INFO_API_TOKEN;

  useEffect(() => {
    // Fetch countries
    fetch('https://restcountries.com/v3.1/all')
      .then((response) => response.json())
      .then((data) => {
        const countryList = data.map((country: any) => ({
          code: country.cca2,
          name: country.name.common,
          flag: country.flags.svg,
        }));
        setCountries(countryList);
      });

    // Get user country code based on their IP
    const getUserCountry = async () => {
      try {
        const res = await axios.get(`https://ipinfo.io?token=61b212b0240679`); // Replace with your API token
        const countryCode = res.data.country;
        setUserCountryCode(countryCode);
      } catch (error) {
        console.error('Error fetching user country:', error);
      }
    };

    getUserCountry();
  }, []);

  const toggleDropdown = () => {
    setIsOpen(!isOpen);
  };

  const handleCountrySelect = (country: Country) => {
    onCountryChange(country.code);
    setIsOpen(false);
  };

  useEffect(() => {
    // Set default country based on user location if not selected
    if (!selectedCountry && userCountryCode) {
      onCountryChange(userCountryCode);
    }
  }, [userCountryCode, selectedCountry, onCountryChange]);

  return (
    <div className="relative">
      <button
        type="button"
        onClick={toggleDropdown}
        className="btn btn-sm !rounded-sm !px-1 !py-1 !min-h-0 !h-auto"
        disabled={disabled}
      >
        {selectedCountry ? (
          <img
            src={countries.find((country) => country.code === selectedCountry)?.flag || ''}
            alt="Country Flag"
            className="w-6 h-4 object-cover"
          />
        ) : (
          <span className="mr-2">Select a Country</span>
        )}
      </button>

      {isOpen && (
        <div
          className="absolute top-full mt-2 w-full bg-white shadow-lg border border-gray-300 rounded-lg max-h-60 overflow-y-auto z-10"
        >
          {countries.map((country) => (
            <div
              key={country.code}
              onClick={() => handleCountrySelect(country)}
              className="flex items-center p-2 cursor-pointer hover:bg-gray-100"
            >
              <img
                src={country.flag}
                alt={country.name}
                className="w-5 h-5 mr-2"
              />
              <span>{country.name}</span>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default CountryDropdown;
