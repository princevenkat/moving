import { useState } from "react";
import { useForm } from "@inertiajs/react";

interface Inquiry {
  id: number;
  new_home_type?: string;
  new_home_floor?: string;
  new_home_easy_access?: string;
  new_home_accessibility_long_distance?: boolean;
  new_home_accessibility_distance_meters?: string;
  new_home_accessibility_has_steps?: boolean;
  new_home_accessibility_steps?: string;
  new_home_accessibility_impeded?: boolean;
  new_home_accessibility_notes?: string;
  new_home_people?: string;
  new_home_boxes?: string;
  new_home_additional_services?: string[];
}

interface NewHomeProps {
  inquiry: Inquiry;
}

export default function NewHome({ inquiry }: NewHomeProps) {
  const { data, setData, post, processing, errors } = useForm({
    new_home_type: inquiry.new_home_type || "",
    new_home_floor: inquiry.new_home_floor || "",
    new_home_easy_access: inquiry.new_home_easy_access || "",
    new_home_accessibility_long_distance: inquiry.new_home_accessibility_long_distance || false,
    new_home_accessibility_distance_meters: inquiry.new_home_accessibility_distance_meters || "",
    new_home_accessibility_has_steps: inquiry.new_home_accessibility_has_steps || false,
    new_home_accessibility_steps: inquiry.new_home_accessibility_steps || "",
    new_home_accessibility_impeded: inquiry.new_home_accessibility_impeded || false,
    new_home_accessibility_notes: inquiry.new_home_accessibility_notes || "",
    new_home_people: inquiry.new_home_people || "",
    new_home_boxes: inquiry.new_home_boxes || "",
    new_home_additional_services: inquiry.new_home_additional_services || [],
  });

  const [step, setStep] = useState(1);

  function nextStep() {
    setStep((prev) => prev + 1);
  }
  function prevStep() {
    setStep((prev) => prev - 1);
  }

  function submit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    post(route("inquiry.update_new_home", { id: inquiry.id }));
  }

  return (
    <form onSubmit={submit}>
      <h2>New Home Details</h2>
      {step === 1 && (
        <div>
          <h3>Where are you moving into?</h3>
          {["house", "apartment", "shared_flat", "storage", "office"].map((type) => (
            <button key={type} onClick={() => setData("new_home_type", type)}>
              {type.charAt(0).toUpperCase() + type.slice(1)}
            </button>
          ))}
          <button onClick={nextStep}>Continue</button>
        </div>
      )}

      {step === 2 && (
        <div>
          <h3>Floors</h3>
          {data.new_home_type === "house" ? (
            <input type="number" value={data.new_home_floor} onChange={(e) => setData("new_home_floor", e.target.value)} />
          ) : (
            ["Basement", "Ground floor", "Mezzanine floor", "1", "2", "3", "4", "5+"].map((floor) => (
              <button key={floor} onClick={() => setData("new_home_floor", floor)}>{floor}</button>
            ))
          )}
          <button onClick={prevStep}>Back</button>
          <button onClick={nextStep}>Continue</button>
        </div>
      )}

      {step === 3 && (
        <div>
          <h3>Easy Access</h3>
          {["No", "Yes, for 2-3 people", "Yes, for 4-5 people", "Yes, for 6+ people", "Yes, goods lift for 10+ people"].map((option) => (
            <button key={option} onClick={() => setData("new_home_easy_access", option)}>{option}</button>
          ))}
          <button onClick={prevStep}>Back</button>
          <button onClick={nextStep}>Continue</button>
        </div>
      )}

      {step === 4 && (
        <div>
          <h3>Accessibility</h3>
          <label>
            <input type="checkbox" checked={data.new_home_accessibility_long_distance} onChange={(e) => setData("new_home_accessibility_long_distance", e.target.checked)} />
            Is longer than 5 meters
          </label>
          {data.new_home_accessibility_long_distance && <input type="number" value={data.new_home_accessibility_distance_meters} onChange={(e) => setData("new_home_accessibility_distance_meters", e.target.value)} />}

          <label>
            <input type="checkbox" checked={data.new_home_accessibility_has_steps} onChange={(e) => setData("new_home_accessibility_has_steps", e.target.checked)} />
            Has steps
          </label>
          {data.new_home_accessibility_has_steps && <input type="number" value={data.new_home_accessibility_steps} onChange={(e) => setData("new_home_accessibility_steps", e.target.value)} />}

          <label>
            <input type="checkbox" checked={data.new_home_accessibility_impeded} onChange={(e) => setData("new_home_accessibility_impeded", e.target.checked)} />
            Is otherwise impeded (e.g. maisonette)
          </label>
          {data.new_home_accessibility_impeded && <textarea value={data.new_home_accessibility_notes} onChange={(e) => setData("new_home_accessibility_notes", e.target.value)} />}

          <button onClick={prevStep}>Back</button>
          <button onClick={nextStep}>Continue</button>
        </div>
      )}

      {step === 5 && (
        <div>
          <h3>People and Moving Boxes</h3>
          <label>People</label>
          <input type="number" value={data.new_home_people} onChange={(e) => setData("new_home_people", e.target.value)} />
          <label>Boxes</label>
          <input type="number" value={data.new_home_boxes} onChange={(e) => setData("new_home_boxes", e.target.value)} />

          <button onClick={prevStep}>Back</button>
          <button onClick={nextStep}>Continue</button>
        </div>
      )}

      {step === 6 && (
        <div>
          <h3>Additional Services</h3>
          {["Disassembling/ Assembling of furniture", "Furniture lift", "Packing boxes", "Dismounting lamps", "Disposal per m3", "Ordering floorliner"].map((service) => (
            <label key={service}>
              <input type="checkbox" checked={data.new_home_additional_services.includes(service)} onChange={() => setData("new_home_additional_services", data.new_home_additional_services.includes(service) ? data.new_home_additional_services.filter((s) => s !== service) : [...data.new_home_additional_services, service])} />
              {service}
            </label>
          ))}
          <button onClick={prevStep}>Back</button>
          <button  disabled={processing}>Continue to Step 4</button>
        </div>
      )}
    </form>
  );
}
