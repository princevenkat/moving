import { useForm } from "@inertiajs/react";
import { useState } from "react";

export default function StartInquiry() {
  const { data, setData, post, processing, errors } = useForm({
    service: "moving",
    current_country: "",
    current_zip: "",
    current_city: "",
    destination_country: "",
    destination_zip: "",
    destination_city: "",
    email: "",
  });

  const [includeDestination, setIncludeDestination] = useState(false);

  function submit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    post(route("inquiry.store"));
  }

  return (
    <div>
      <h2>Start Your Inquiry</h2>

      <form onSubmit={submit}>
        <div>
          <label>Choose Service</label>
          <select value={data.service} onChange={(e) => setData("service", e.target.value)}>
            <option value="moving">Only Moving</option>
            <option value="cleaning">Only Handover Cleaning</option>
            <option value="moving-handover-cleaning">Moving and Handover Cleaning</option>
          </select>
          {errors.service && <p>{errors.service}</p>}
        </div>

        <div>
          <h3>Moving From</h3>
          <label>Country</label>
          <input type="text" value={data.current_country} onChange={(e) => setData("current_country", e.target.value)} />
          {errors.current_country && <p>{errors.current_country}</p>}

          <label>Zip Code</label>
          <input type="text" value={data.current_zip} onChange={(e) => setData("current_zip", e.target.value)} />
          {errors.current_zip && <p>{errors.current_zip}</p>}

          <label>City</label>
          <input type="text" value={data.current_city} onChange={(e) => setData("current_city", e.target.value)} />
          {errors.current_city && <p>{errors.current_city}</p>}
        </div>

        {/*<div>*/}
        {/*  <label>*/}
        {/*    <input type="checkbox" checked={includeDestination} onChange={() => setIncludeDestination(!includeDestination)} />*/}
        {/*    Add Destination Details*/}
        {/*  </label>*/}
        {/*</div>*/}

        {/*{includeDestination && (*/}
          <div>
            <h3>Moving To (Optional)</h3>
            <label>Country</label>
            <input type="text" value={data.destination_country} onChange={(e) => setData("destination_country", e.target.value)} />

            <label>Zip Code</label>
            <input type="text" value={data.destination_zip} onChange={(e) => setData("destination_zip", e.target.value)} />

            <label>City</label>
            <input type="text" value={data.destination_city} onChange={(e) => setData("destination_city", e.target.value)} />
          </div>
        {/*)}*/}

        <div>
          <label>Email Address</label>
          <input type="email" value={data.email} onChange={(e) => setData("email", e.target.value)} />
          {errors.email && <p>{errors.email}</p>}
        </div>

        <button type="submit" disabled={processing}>Start Inquiry</button>
      </form>
    </div>
  );
}
