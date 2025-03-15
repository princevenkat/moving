import {Link, useForm} from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import InputLabel from "@/Components/Core/InputLabel";
import CountryDropdown from "@/Components/App/CountryDropdown";
import {useEffect} from "react";
import {getFromLocalStorage, saveToLocalStorage} from "@/utils/auth";

export default function Step1() {

  const queryParams = new URLSearchParams(window.location.search);
  const serviceTypeFromQuery = queryParams.get('service_type'); // Get the 'service_type' query parameter


  //const savedData = getFromLocalStorage("inquiry_form_step_1") || {};
  const savedData = JSON.parse(localStorage.getItem("inquiry_form_step_1") || "{}");

  const { data, setData, post, processing } = useForm({
    service_type: serviceTypeFromQuery || "",
    current_country: savedData.current_country || "",
    current_zip: savedData.current_zip || "",
    current_city: savedData.current_city || "",
    destination_country: savedData.destination_country || "",
    destination_zip: savedData.destination_zip || "",
    destination_city: savedData.destination_city || "",
    email: savedData.email || "",
  });

  // useEffect(() => {
  //   saveToLocalStorage("inquiry_form_step_1", data);
  // }, [data]);

  useEffect(() => {
    localStorage.setItem("inquiry_form_step_1", JSON.stringify(data));
  }, [data]);

  function submit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    post(route("inquiry.store"));
  }


  const isMovingFromCountryDisabled = !!data.current_country;
  return (
    <AuthenticatedLayout>



      <div className='bg-white px-10 py-10 shadow-md  sm:rounded-lg max-w-lg mt-6 mx-auto '>
        <div className="mb-5 text-center">
          <h1 className='text-3xl font-bold text-center'>Start Inquiry</h1>
          <p>Find the best offers from companies within your region</p>
        </div>
        <form onSubmit={submit}>

        <div className="mt-4">
          <label className='label'><span className='label-text font-medium text-lg'>Choose Service</span></label>
          <select className="select select-bordered w-full " value={data.service_type} onChange={(e) => setData("service_type", e.target.value)}>
            <option value="moving">Only Moving</option>
            <option value="cleaning">Only Handover Cleaning</option>
            <option value="both">Moving and Cleaning</option>
          </select>
        </div>

        <h2 className='text-lg mt-5 mb-4 font-medium p-2 shadow text-center rounded-full bg-gray-100'>Where should your Service take place?</h2>
        <div className="mt-4">
          <div className="mb-2 flex items-end justify-end">
            <h3 className='font-medium text-sm'>Moving From</h3>
            <div className=' relative flex-1 text-right'>
            <CountryDropdown
              selectedCountry={data.current_country}
              onCountryChange={(countryCode) => setData("current_country", countryCode)}
              disabled={isMovingFromCountryDisabled}
            />
            </div>
          </div>
          <div className="join join-horizontal">
            <input className='input input-bordered block  join-item w-2/4' type="text" placeholder="ZIP" value={data.current_zip} onChange={(e) => setData("current_zip", e.target.value)} />
            <input className='input input-bordered block w-full join-item' type="text" placeholder="CITY" value={data.current_city} onChange={(e) => setData("current_city", e.target.value)} />
          </div>
        </div>


        <div className="mt-4">
          <div className="mb-2 flex items-end justify-end">
            <h3 className='font-medium text-sm'>Moving to</h3>
            <div className=' relative flex-1 text-right'>
            <CountryDropdown
              selectedCountry={data.destination_country}
              onCountryChange={(countryCode) => setData("destination_country", countryCode)}
            /></div>
          </div>

          <div className="join join-horizontal">
            <input className='input input-bordered block  join-item w-2/4' type="text" placeholder="ZIP" value={data.destination_zip} onChange={(e) => setData("destination_zip", e.target.value)} />
            <input className='input input-bordered block w-full join-item' type="text" placeholder="CITY" value={data.destination_city} onChange={(e) => setData("destination_city", e.target.value)} />
          </div>
        </div>

        <div className="mt-4">
          <div className="font-medium text-sm mb-2"><h3>Email Address</h3></div>
          <input className='input input-bordered block w-full' type="email" value={data.email} onChange={(e) => setData("email", e.target.value)} />
        </div>
        <div className='text-sm mt-5 mb-4 text-gray-600'>We use your contact information to send you notifications about your offers.
          <Link href='#' className='ml-1 text-blue-400'>Privacy policy</Link></div>


        <button className='btn btn-neutral mt-4 w-full text-lg' type="submit" disabled={processing}>Start Inquiry</button>
      </form>
      </div>
    </AuthenticatedLayout>
  );
}
